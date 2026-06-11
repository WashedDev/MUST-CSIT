<?php

namespace App\Http\Controllers;

use App\Models\Event;
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
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date|after:now',
            'location'    => 'required|string|max:255',
            'capacity'    => 'nullable|integer|min:0',
            'price'       => 'nullable|numeric|min:0',
            'tag'         => 'nullable|string|max:255',
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
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'capacity'    => 'nullable|integer|min:0',
            'price'       => 'nullable|numeric|min:0',
            'tag'         => 'nullable|string|max:255',
        ]);

        $data['capacity'] = $data['capacity'] !== null && $data['capacity'] !== '' ? (int) $data['capacity'] : 0;
        $data['price'] = $data['price'] !== null && $data['price'] !== '' ? $data['price'] : null;

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
