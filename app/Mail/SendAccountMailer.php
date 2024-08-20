<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendAccountMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $data;

    public $email;
    /**
     * Create a new message instance.
     */
    public function __construct($passwords, $data, $email)
    {
        $this->password = $passwords;
        $this->data = $data;
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OIPI INFO: NOTIFICATION APRES CREATION DE COMPTE',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.send_account_mailer',
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
