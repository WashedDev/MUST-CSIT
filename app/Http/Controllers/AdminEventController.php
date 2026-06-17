<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('bookings')->latest()->paginate(20);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'                => 'required|string|max:255',
            'description'          => 'nullable|string',
            'date'                 => 'required|date|after:now',
            'location'             => 'required|string|max:255',
            'capacity'             => 'nullable|integer|min:0',
            'price'                => 'nullable|numeric|min:0',
            'tag'                  => 'nullable|string|max:255',
            'event_type'           => 'required|in:in_person,virtual,hybrid',
            'registration_deadline'=> 'nullable|date|before_or_equal:date',
            'cancel_deadline'      => 'nullable|date|before_or_equal:date',
        ]);

        $data['capacity'] = $data['capacity'] !== null && $data['capacity'] !== '' ? (int) $data['capacity'] : 0;
        $data['price'] = $data['price'] !== null && $data['price'] !== '' ? $data['price'] : null;

        Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title'                => 'required|string|max:255',
            'description'          => 'nullable|string',
            'date'                 => 'required|date',
            'location'             => 'required|string|max:255',
            'capacity'             => 'nullable|integer|min:0',
            'price'                => 'nullable|numeric|min:0',
            'tag'                  => 'nullable|string|max:255',
            'event_type'           => 'required|in:in_person,virtual,hybrid',
            'registration_deadline'=> 'nullable|date|before_or_equal:date',
            'cancel_deadline'      => 'nullable|date|before_or_equal:date',
        ]);

        $data['capacity'] = $data['capacity'] !== null && $data['capacity'] !== '' ? (int) $data['capacity'] : 0;
        $data['price'] = $data['price'] !== null && $data['price'] !== '' ? $data['price'] : null;

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $bookings = Booking::where('event_id', $event->id)
            ->where('status', 'confirmed')
            ->with('user')
            ->get();

        foreach ($bookings as $booking) {
            Notification::notify(
                $booking->user_id,
                'event_cancelled',
                'Event Cancelled: ' . $event->title,
                'The event "' . $event->title . '" scheduled for ' . $event->date?->format('M d, Y') . ' has been cancelled.',
                route('events.index')
            );
        }

        $event->bookings()->delete();
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted. ' . $bookings->count() . ' attendee(s) notified.');
    }
}
