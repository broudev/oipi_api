<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Notifications extends Mailable
{
    use Queueable, SerializesModels;

    public $notifications;
    public $notifications_data;
    public $employee_data;

    /**
     * Create a new message instance.
     */
    public function __construct($notifications,$notifications_data, $employee_data)
    {
        $this->notifications = $notifications;
        $this->notifications_data = $notifications_data;
        $this->employee_data = $employee_data;

    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'NOTIFICATION',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
