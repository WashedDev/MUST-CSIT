<?php

namespace App\Mail;

use App\Models\Election;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class ElectionClosingReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Election $election,
    ) {
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.election-closing',
            with: ['election' => $this->election],
        );
    }

    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: "Reminder: {$this->election->title} closes soon",
        );
    }
}
