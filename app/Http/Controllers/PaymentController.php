<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\OneKhusaService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function showForm()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('dashboard');
        }

        if ($user->membership_paid) {
            return redirect()->route('dashboard')->with('info', 'Your membership is already paid.');
        }

        $amount = config('membership.fee');
        $currency = config('membership.currency');

        return view('payment.pay', compact('amount', 'currency'));
    }

    public function processPayment(Request $request)
    {
        $user = auth()->user();

        if ($user->membership_paid) {
            return redirect()->route('dashboard');
        }

        $data = $request->validate([
            'gateway' => 'required|string|in:onekhusa',
        ]);

        $amount = config('membership.fee');
        $currency = config('membership.currency');

        $payment = Payment::create([
            'user_id'  => $user->id,
            'type'     => 'membership',
            'amount'   => $amount,
            'currency' => $currency,
            'gateway'  => $data['gateway'],
            'status'   => 'pending',
        ]);

        return $this->initiateOneKhusaRequestToPay($payment);
    }

    protected function initiateOneKhusaRequestToPay(Payment $payment)
    {
        $oneKhusa = app(OneKhusaService::class);

        $reference = 'CSIT-' . $payment->id . '-' . time();

        $payment->update(['gateway_reference' => $reference]);

        if (! $oneKhusa->isConfigured()) {
            $this->completeMembershipPayment($payment);

            return redirect()->route('payment.success')
                ->with('info', 'Payment gateway not configured. Membership marked as paid for testing.');
        }

        $result = $oneKhusa->initiateRequestToPay(
            referenceNumber: $reference,
            description: 'CSIT Society Membership Fee',
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

    public function showTan(Payment $payment)
    {
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        if ($payment->status === 'completed') {
            return redirect()->route('payment.success');
        }

        $tanData = session('payment_tan_' . $payment->id);

        if (! $tanData) {
            return redirect()->route('payment.show')
                ->withErrors(['payment' => 'Payment session expired. Please try again.']);
        }

        return view('payment.tan', [
            'payment'     => $payment,
            'tanNumber'   => $tanData['number'],
            'tanExpiry'   => $tanData['expiry'],
            'amount'      => $payment->amount,
            'description' => 'CSIT Society Membership Fee',
            'itemLabel'   => 'Membership',
        ]);
    }

    public function checkTanStatus(Payment $payment)
    {
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        if ($payment->status === 'completed') {
            return $this->tanPaidResponse($payment);
        }

        if ($payment->gateway_reference && $this->verifyOneKhusaTransaction($payment->gateway_reference)) {
            $payment->update(['status' => 'completed', 'paid_at' => now()]);

            if ($payment->isMembership()) {
                $this->completeMembershipPayment($payment);
            } elseif ($payment->isEventPayment()) {
                $this->completeEventPayment($payment);
            }

            session()->forget('payment_tan_' . $payment->id);

            return $this->tanPaidResponse($payment);
        }

        return back()->withErrors(['status' => 'Payment not yet received. Please try again after sending the funds.']);
    }

    protected function tanPaidResponse(Payment $payment)
    {
        if ($payment->isMembership()) {
            return redirect()->route('payment.success');
        }

        if ($payment->isEventPayment() && $payment->booking) {
            return redirect()->route('events.show', $payment->booking->event)
                ->with('success', 'Payment received. Your booking is confirmed.');
        }

        return redirect()->route('dashboard');
    }

    public function handleWebhook(Request $request)
    {
        $reference = $request->input('metaData.referenceNumber')
            ?? $request->input('sourceReferenceNumber');

        if (! $reference) {
            return response('OK', 200);
        }

        $payment = Payment::where('gateway_reference', $reference)->first();

        if ($payment && $payment->status !== 'completed') {
            $eventCode = $request->header('X-OneKhusa-Webhook-Event', 'payrequest.success');

            if (str_contains($eventCode, 'success')) {
                $payment->update(['status' => 'completed', 'paid_at' => now()]);

                if ($payment->isMembership()) {
                    $this->completeMembershipPayment($payment);
                } elseif ($payment->isEventPayment()) {
                    $this->completeEventPayment($payment);
                }

                session()->forget('payment_tan_' . $payment->id);
            }
        }

        return response('OK', 200);
    }

    public function success()
    {
        $user = auth()->user();

        if ($user->membership_paid) {
            return view('payment.success');
        }

        $payment = Payment::where('user_id', $user->id)
            ->where('type', 'membership')
            ->where('status', 'completed')
            ->latest()
            ->first();

        if ($payment) {
            $this->completeMembershipPayment($payment);
            return view('payment.success');
        }

        $payment = Payment::where('user_id', $user->id)
            ->where('type', 'membership')
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($payment) {
            if ($payment->gateway_reference) {
                $verified = $this->verifyOneKhusaTransaction($payment->gateway_reference);
                if ($verified) {
                    $this->completeMembershipPayment($payment);
                    return view('payment.success');
                }
            } else {
                $this->completeMembershipPayment($payment);
                return view('payment.success');
            }
        }

        return redirect()->route('payment.show');
    }

    public function cancel()
    {
        return redirect()->route('payment.show')
            ->withErrors(['payment' => 'Payment was cancelled. Please try again.']);
    }

    protected function completeMembershipPayment(Payment $payment)
    {
        if ($payment->status !== 'completed') {
            $payment->update(['status' => 'completed', 'paid_at' => now()]);
        }
        $payment->user->update(['membership_paid' => true, 'paid_at' => $payment->paid_at ?? now()]);
    }

    protected function completeEventPayment(Payment $payment)
    {
        $booking = $payment->booking;
        if ($booking && $booking->status === 'pending_payment') {
            $booking->update(['status' => 'confirmed']);
        }
    }

    protected function verifyOneKhusaTransaction(string $reference): bool
    {
        $oneKhusa = app(OneKhusaService::class);

        if (! $oneKhusa->isConfigured()) {
            return true;
        }

        $transaction = $oneKhusa->getTransaction($reference);

        if ($transaction && ($transaction['transaction']['transactionStatusCode'] ?? '') === 'S') {
            return true;
        }

        return false;
    }
}
