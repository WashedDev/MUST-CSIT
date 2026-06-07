<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Election;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('bookings')->latest('date')->paginate(10);
        $elections = Election::withCount('votes')->latest()->get();
        return view('pages.events', compact('events', 'elections'));
    }

    public function show(Event $event)
    {
        $event->loadCount('bookings');
        $userBooking = Booking::where('event_id', $event->id)
            ->where('user_id', auth()->id())->first();

        return view('events.show', compact('event', 'userBooking'));
    }

    public function book(Request $request, Event $event)
    {
        if ($event->date->isPast()) {
            return back()->withErrors(['event' => 'This event has already passed.']);
        }

        if ($event->availableSeats() <= 0) {
            return back()->withErrors(['event' => 'No seats available for this event.']);
        }

        if (Booking::where('event_id', $event->id)->where('user_id', auth()->id())->exists()) {
            return back()->withErrors(['booking' => 'You are already booked for this event.']);
        }

        Booking::create([
            'event_id' => $event->id,
            'user_id'  => auth()->id(),
            'status'   => 'confirmed',
        ]);

        return redirect()->route('events.show', $event)
            ->with('success', 'You have been booked for this event.');
    }

    public function cancel(Event $event)
    {
        $booking = Booking::where('event_id', $event->id)
            ->where('user_id', auth()->id())->firstOrFail();

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('events.show', $event)
            ->with('success', 'Your booking has been cancelled.');
    }
}
