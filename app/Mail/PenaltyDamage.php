<?php

namespace App\Mail;

use App\Models\Damage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PenaltyDamage extends Mailable
{
    use Queueable, SerializesModels;

    public $damage;
    public $isUpdate;

    /**
     * Create a new message instance.
     */
    public function __construct($damage, $isUpdate = false)
    {
        $this->damage = $damage;
        $this->isUpdate = $isUpdate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Segnalazione danno e richiesta addebito',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.penalty-damage',
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
