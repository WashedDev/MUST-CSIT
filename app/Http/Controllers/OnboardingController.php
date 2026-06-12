<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function index()
    {
        if (auth()->user()->onboarding_completed) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.index');
    }

    public function complete()
    {
        auth()->user()->update(['onboarding_completed' => true]);

        return redirect()->route('dashboard')
            ->with('success', 'Welcome aboard! Your profile is all set.');
    }
}
