<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ImputedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $imputation_data;
    public $employee_data;
    public $objet_courrier;


    /**
     * Create a new message instance.
     */
    public function __construct($imputation_data, $employee_data, $objet_courrier)
    {
        $this->imputation_data = $imputation_data;
        $this->employee_data = $employee_data;
        $this->objet_courrier = $objet_courrier;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'IMPUTATION DE COURRIER',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.imputation_notification',
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
