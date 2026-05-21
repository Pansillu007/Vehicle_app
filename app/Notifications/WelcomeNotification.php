<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to VehiclePro')
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('Your account is ready. Start tracking vehicles, maintenance, and costs in one dashboard.')
            ->action('Open Dashboard', route('dashboard'))
            ->line('Thank you for using VehiclePro!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Welcome to VehiclePro! Your account is ready.',
            'url' => route('dashboard'),
        ];
    }
}
