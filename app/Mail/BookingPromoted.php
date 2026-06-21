<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class BookingPromoted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
    ) {
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking-promoted',
            with: [
                'booking' => $this->booking,
                'event'   => $this->booking->event,
            ],
        );
    }

    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: 'Spot Opened Up: ' . $this->booking->event->title,
        );
    }
}
