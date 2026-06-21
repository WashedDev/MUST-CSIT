<?php

namespace App\Mail;

use App\Models\Election;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class ElectionOpened extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Election $election,
    ) {
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.election-opened',
            with: ['election' => $this->election],
        );
    }

    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: "Voting is now open: {$this->election->title}",
        );
    }
}
