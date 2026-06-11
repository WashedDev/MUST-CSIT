<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Election;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->query('view', 'grid');

        if ($view === 'calendar') {
            $month = $request->query('month', now()->format('Y-m'));
            $date = Carbon::parse($month . '-01');
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $events = Event::withCount('bookings')
                ->withExists(['bookings as user_booked' => fn($q) => $q->where('user_id', auth()->id())])
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->orderBy('date')
                ->get()
                ->groupBy(fn($e) => $e->date->format('Y-m-d'));

            $elections = Election::withCount('votes')->latest()->get();

            return view('pages.events', compact('events', 'elections', 'view', 'date'));
        }

        $events = Event::withCount('bookings')
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

        if (! $event->hasUnlimitedCapacity() && $event->availableSeats() <= 0) {
            return back()->withErrors(['event' => 'No seats available for this event.']);
        }

        if (Booking::where('event_id', $event->id)->where('user_id', auth()->id())->exists()) {
            return back()->withErrors(['booking' => 'You are already booked for this event.']);
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
                'gateway'   => 'ctechpay',
                'status'    => 'pending',
            ]);

            return $this->initiateEventPayment($payment, $event);
        }

        Notification::notify(
            auth()->id(),
            'booking_confirmed',
            'Booking Confirmed',
            "You are booked for {$event->title} on {$event->date->format('M d, Y H:i')}.",
            route('events.show', $event)
        );

        return redirect()->route('events.show', $event)
            ->with('success', 'You have been booked for this event.');
    }

    protected function initiateEventPayment(Payment $payment, Event $event)
    {
        $apiToken = config('services.ctechpay.token');
        $baseUrl = config('services.ctechpay.base_url');

        $payload = [
            'token'               => $apiToken,
            'amount'              => (int) $payment->amount,
            'category_flag'       => 'EVENT_REGISTRATION',
            'customer_reference'  => 'CSIT-EVT-' . $payment->id . '-' . time(),
            'customer_message'    => 'Registration for ' . $event->title,
            'merchantAttributes'  => true,
            'redirectUrl'         => route('events.show', $event),
            'cancelUrl'           => route('events.show', $event),
            'cancelText'          => 'Go Back',
            'skipConfirmationPage' => false,
        ];

        $payment->update(['gateway_reference' => $payload['customer_reference']]);

        if (! $apiToken) {
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

        try {
            $response = Http::post($baseUrl . '/api/v1/orders', $payload);

            $body = $response->json();

            if ($response->successful() && ! empty($body['payment_page_URL'])) {
                $payment->update(['gateway_reference' => $body['order_reference'] ?? $payload['customer_reference']]);

                return redirect($body['payment_page_URL']);
            }

            $payment->update(['status' => 'failed']);
            return back()->withErrors(['payment' => 'Payment initiation failed. Please try again.']);
        } catch (\Exception $e) {
            $payment->update(['status' => 'failed']);
            return back()->withErrors(['payment' => 'Could not connect to payment gateway. Please try again.']);
        }
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
