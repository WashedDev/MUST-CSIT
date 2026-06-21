<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class MembershipRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public ?string $reason = null,
    ) {
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.membership-rejected',
            with: [
                'user' => $this->user,
                'reason' => $this->reason,
            ],
        );
    }

    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: 'Membership Application Status — MUST CSIT Society',
        );
    }
}
