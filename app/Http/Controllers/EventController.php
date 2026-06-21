<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Mail\BookingPromoted;
use App\Models\Booking;
use App\Models\Election;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Payment;
use App\Services\OneKhusaService;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $view = $request->query('view', 'grid');

        $visibilityFilter = fn($q) => $q->where(function ($q) use ($user) {
            $q->where('visibility', 'all');
            if ($user->isAdmin() || $user->role === 'executive') {
                $q->orWhere('visibility', 'restricted');
            }
        });

        if ($view === 'calendar') {
            $month = $request->query('month', now()->format('Y-m'));
            $date = Carbon::parse($month . '-01');
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $events = Event::withCount('bookings')
                ->withExists(['bookings as user_booked' => fn($q) => $q->where('user_id', auth()->id())])
                ->where($visibilityFilter)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->orderBy('date')
                ->get()
                ->groupBy(fn($e) => $e->date->format('Y-m-d'));

            $elections = Election::withCount('votes')->latest()->get();

            return view('pages.events', compact('events', 'elections', 'view', 'date'));
        }

        $events = Event::withCount('bookings')
            ->where($visibilityFilter)
            ->withExists(['bookings as user_booked' => fn($q) => $q->where('user_id', auth()->id())])
            ->orderByDesc('user_booked')
            ->latest('date')
            ->paginate(10);

        $elections = Election::withCount('votes')->latest()->get();

        return view('pages.events', compact('events', 'elections', 'view'));
    }

    public function show(Event $event)
    {
        $event->loadCount('bookings');
        $userBooking = Booking::where('event_id', $event->id)
            ->where('user_id', auth()->id())->first();

        return view('events.show', compact('event', 'userBooking'));
    }

    public function details(Event $event)
    {
        $event->loadCount('bookings');
        $userBooking = Booking::where('event_id', $event->id)
            ->where('user_id', auth()->id())->first();

        $html = view('events.modal-content', compact('event', 'userBooking'))->render();

        return response()->json(['html' => $html]);
    }

    public function book(Request $request, Event $event)
    {
        if ($event->date->isPast()) {
            return back()->withErrors(['event' => 'This event has already passed.']);
        }

        if ($event->registration_deadline && now()->greaterThan($event->registration_deadline)) {
            return back()->withErrors(['event' => 'Registration deadline for this event has passed.']);
        }

        if (Booking::where('event_id', $event->id)->where('user_id', auth()->id())->exists()) {
            return back()->withErrors(['booking' => 'You are already booked for this event.']);
        }

        $isFull = ! $event->hasUnlimitedCapacity() && $event->availableSeats() <= 0;

        if ($isFull) {
            Booking::create([
                'event_id' => $event->id,
                'user_id'  => auth()->id(),
                'status'   => 'waitlisted',
            ]);

            return redirect()->route('events.show', $event)
                ->with('info', 'The event is full. You have been added to the waitlist.');
        }

        $booking = Booking::create([
            'event_id' => $event->id,
            'user_id'  => auth()->id(),
            'status'   => $event->isPaid() ? 'pending_payment' : 'confirmed',
        ]);

        if ($event->isPaid()) {
            $payment = Payment::create([
                'user_id'   => auth()->id(),
                'booking_id'=> $booking->id,
                'type'      => 'event',
                'amount'    => $event->price,
                'currency'  => config('membership.currency', 'MWK'),
                'gateway'   => 'onekhusa',
                'status'    => 'pending',
            ]);

            return $this->initiateEventRequestToPay($payment, $event);
        }

        Notification::notify(
            auth()->id(),
            'booking_confirmed',
            'Booking Confirmed',
            "You are booked for {$event->title} on {$event->date->format('M d, Y H:i')}.",
            route('events.show', $event)
        );

        Mail::to(auth()->user())->queue(new BookingConfirmation($booking));

        return redirect()->route('events.show', $event)
            ->with('success', 'You have been booked for this event.');
    }

    protected function initiateEventRequestToPay(Payment $payment, Event $event)
    {
        $oneKhusa = app(OneKhusaService::class);

        $reference = 'CSIT-EVT-' . $payment->id . '-' . time();

        $payment->update(['gateway_reference' => $reference]);

        if (! $oneKhusa->isConfigured()) {
            $payment->update(['status' => 'completed', 'paid_at' => now()]);
            $booking = $payment->booking;
            if ($booking) {
                $booking->update(['status' => 'confirmed']);
            }

            Notification::notify(
                auth()->id(),
                'payment_received',
                'Payment Received',
                "Your payment for {$event->title} has been confirmed.",
                route('events.show', $event)
            );

            return redirect()->route('events.show', $event)
                ->with('success', 'Booking confirmed. (Payment gateway not configured — marked as paid for testing.)');
        }

        $result = $oneKhusa->initiateRequestToPay(
            referenceNumber: $reference,
            description: 'Registration for ' . $event->title,
            amount: (float) $payment->amount,
            capturedBy: auth()->user()->email,
        );

        if ($result && ! empty($result['timedAccountNumber'])) {
            $payment->update(['gateway_reference' => $result['referenceNumber'] ?? $reference]);

            session()->put('payment_tan_' . $payment->id, [
                'number' => $result['timedAccountNumber'],
                'expiry' => $result['expiryDate'],
            ]);

            return redirect()->route('payment.tan', $payment->id);
        }

        $payment->update(['status' => 'failed']);

        return back()->withErrors(['payment' => 'Payment initiation failed. Please try again.']);
    }

    public function cancel(Event $event)
    {
        $booking = Booking::where('event_id', $event->id)
            ->where('user_id', auth()->id())->firstOrFail();

        if ($event->cancel_deadline && now()->greaterThan($event->cancel_deadline)) {
            return back()->withErrors(['cancel' => 'The cancellation deadline for this event has passed.']);
        }

        $booking->update(['status' => 'cancelled']);

        $waitlisted = Booking::where('event_id', $event->id)
            ->where('status', 'waitlisted')
            ->oldest()
            ->first();

        if ($waitlisted) {
            $newStatus = $event->isPaid() ? 'pending_payment' : 'confirmed';
            $waitlisted->update(['status' => $newStatus]);

            Notification::notify(
                $waitlisted->user_id,
                'booking_confirmed',
                'Spot Opened!',
                "A spot opened up for {$event->title}. Your booking has been confirmed.",
                route('events.show', $event)
            );

            Mail::to($waitlisted->user)->queue(new BookingPromoted($waitlisted));
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Your booking has been cancelled.');
    }

    public function exportAttendees(Event $event)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }

        $bookings = Booking::where('event_id', $event->id)
            ->where('status', 'confirmed')
            ->with('user')
            ->get();

        $csv = "Name,Email,Reg Number,Programme,Year\n";
        foreach ($bookings as $booking) {
            $u = $booking->user;
            $csv .= '"' . $u->name . '","' . $u->email . '","' . ($u->reg_number ?? '') . '","' . ($u->programme ?? '') . '","' . ($u->year ?? '') . '"' . "\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="attendees-' . $event->id . '.csv"',
        ]);
    }
}
