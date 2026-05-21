<?php

namespace App\Console\Commands;

use App\Mail\ServiceReminderMail;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\ServiceDueNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendServiceReminders extends Command
{
    protected $signature = 'vehiclepro:send-reminders';

    protected $description = 'Send service due notifications and emails to users';

    public function handle(): int
    {
        $today = Carbon::today();
        $count = 0;

        User::with('vehicles')->chunk(50, function ($users) use ($today, &$count) {
            foreach ($users as $user) {
                foreach ($user->vehicles as $vehicle) {
                    $due = false;
                    $message = '';

                    if ($vehicle->next_service_due_date && $vehicle->next_service_due_date->lte($today->copy()->addDays(7))) {
                        $due = true;
                        $message = $vehicle->next_service_due_date->lt($today)
                            ? 'Service overdue since '.$vehicle->next_service_due_date->format('M d, Y')
                            : 'Service due on '.$vehicle->next_service_due_date->format('M d, Y');
                    }

                    if ($vehicle->next_service_due_mileage && $vehicle->mileage >= $vehicle->next_service_due_mileage - 500) {
                        $due = true;
                        $message .= ($message ? ' · ' : '').'Mileage service threshold approaching';
                    }

                    if ($due) {
                        $user->notify(new ServiceDueNotification($vehicle));
                        Mail::to($user->email)->send(new ServiceReminderMail($vehicle, $message));
                        $count++;
                    }
                }
            }
        });

        $this->info("Sent {$count} reminder(s).");

        return self::SUCCESS;
    }
}
