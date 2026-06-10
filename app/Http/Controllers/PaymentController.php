<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
            'gateway' => 'required|string|in:ctechpay',
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

        return $this->initiateCtechPayOrder($payment);
    }

    protected function initiateCtechPayOrder(Payment $payment)
    {
        $apiToken = config('services.ctechpay.token');
        $baseUrl = config('services.ctechpay.base_url');

        $payload = [
            'token'               => $apiToken,
            'amount'              => (int) $payment->amount,
            'category_flag'       => 'MEMBERSHIP_FEE',
            'customer_reference'  => 'CSIT-' . $payment->id . '-' . time(),
            'customer_message'    => 'CSIT Society Membership Fee',
            'merchantAttributes'  => true,
            'redirectUrl'         => route('payment.success'),
            'cancelUrl'           => route('payment.cancel'),
            'cancelText'          => 'Go Back',
            'skipConfirmationPage' => false,
        ];

        $payment->update(['gateway_reference' => $payload['customer_reference']]);

        if (! $apiToken) {
            $this->completeMembershipPayment($payment);

            return redirect()->route('payment.success')
                ->with('info', 'Payment gateway not configured. Membership marked as paid for testing.');
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

    public function handleWebhook(Request $request)
    {
        $orderRef = $request->input('orderRef');
        $status = $request->input('status');

        if ($orderRef) {
            $payment = Payment::where('gateway_reference', $orderRef)->first();

            if ($payment && $payment->status !== 'completed') {
                $verified = $this->verifyCtechPayOrder($orderRef);

                if ($verified) {
                    $payment->update(['status' => 'completed', 'paid_at' => now()]);

                    if ($payment->isMembership()) {
                        $this->completeMembershipPayment($payment);
                    } elseif ($payment->isEventPayment()) {
                        $this->completeEventPayment($payment);
                    }
                } elseif ($status === 'FAILED') {
                    $payment->update(['status' => 'failed']);
                }
            }
        }

        return response('OK', 200);
    }

    protected function verifyCtechPayOrder(string $orderRef): bool
    {
        $apiToken = config('services.ctechpay.token');
        $baseUrl = config('services.ctechpay.base_url');

        if (! $apiToken) {
            return true;
        }

        try {
            $response = Http::get($baseUrl . '/api/v1/orders/status', [
                'orderRef' => $orderRef,
                'token'    => $apiToken,
            ]);

            $body = $response->json();

            return $response->successful() && ($body['status'] ?? '') === 'COMPLETED';
        } catch (\Exception $e) {
            return false;
        }
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
                $verified = $this->verifyCtechPayOrder($payment->gateway_reference);
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
}
