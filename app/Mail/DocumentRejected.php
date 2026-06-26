<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $fields;

    /**
     * Create a new message instance.
     */
    public function __construct($booking, array $fields)
    {
        $this->booking = $booking;

        $order = [
            'driver_license_front',
            'driver_license_back',
            'id_card_front',
            'id_card_back'
        ];

        $this->fields = array_values(array_filter($order, function ($item) use ($fields) {
            return in_array($item, $fields);
        }));
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Documenti non validi',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.document-rejected',
            with: [
                'booking' => $this->booking,
                'fields'  => $this->fields,
                'labels'  => [
                    'driver_license_front' => 'Patente di Guida - Fronte',
                    'driver_license_back'  => 'Patente di Guida - Retro',
                    'id_card_front'        => 'Carta d\'Identità - Fronte',
                    'id_card_back'         => 'Carta d\'Identità - Retro',
                ]
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
