<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMembershipPayment
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->isAdmin()) {
            if (! $user->membership_paid) {
                return redirect()->route('payment.show');
            }
            if ($user->membership_status === 'pending' && ! $user->approved) {
                return redirect()->route('profile')->with('info', 'Your membership is pending admin approval.');
            }
        }

        return $next($request);
    }
}
