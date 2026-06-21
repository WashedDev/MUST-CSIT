<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($booking->status !== 'confirmed') {
            return back()->withErrors(['ticket' => 'Ticket is only available for confirmed bookings.']);
        }

        $booking->load('event', 'user');

        $data = json_encode([
            'booking'  => $booking->id,
            'event'    => $booking->event->title,
            'date'     => $booking->event->date->toIso8601String(),
            'member'   => $booking->user->name,
            'location' => $booking->event->location,
        ]);

        $qrCode = QrCode::format('svg')->size(250)->generate($data);

        return view('events.ticket', compact('booking', 'qrCode'));
    }
}
