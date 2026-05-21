<?php

namespace App\Mail;

use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Vehicle $vehicle, public string $statusMessage) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Service Reminder: '.$this->vehicle->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.service-reminder',
            with: [
                'vehicle' => $this->vehicle,
                'statusMessage' => $this->statusMessage,
                'url' => route('vehicles.show', $this->vehicle),
            ],
        );
    }
}
