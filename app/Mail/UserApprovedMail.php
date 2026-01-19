<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $notes;

    public function __construct(User $user, $notes = null)
    {
        $this->user = $user;
        $this->notes = $notes;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Conta Aprovada - Portal Escolar Alegria Pedro. Já pode fazer Login',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user-approved',
            with: [
                'user' => $this->user,
                'notes' => $this->notes,
                'loginUrl' => route('login'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
