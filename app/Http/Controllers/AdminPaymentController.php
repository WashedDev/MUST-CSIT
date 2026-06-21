<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('user')->latest()->paginate(20);

        $unpaidCount = User::where('membership_paid', false)
            ->where('role', 'member')
            ->count();

        $paidCount = User::where('membership_paid', true)->count();

        return view('admin.payments.index', compact('payments', 'unpaidCount', 'paidCount'));
    }

    public function markPaid(Request $request, Payment $payment)
    {
        if ($payment->status !== 'completed') {
            $payment->update(['status' => 'completed', 'paid_at' => now()]);
        }

        $payment->user->forceFill(['membership_paid' => true, 'paid_at' => $payment->paid_at ?? now()])->save();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment marked as completed.');
    }
}
