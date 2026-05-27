<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        if (! str_ends_with($googleUser->getEmail(), '@must.ac.mw')) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Only @must.ac.mw email addresses are allowed.']);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            Auth::login($user);
            return redirect()->intended(route('dashboard'));
        }

        $name = $googleUser->getName() ?? $googleUser->getNickname() ?? '';
        $parts = explode(' ', $name, 2);

        session(['oauth' => [
            'firstname' => $parts[0] ?? '',
            'lastname'  => $parts[1] ?? '',
            'email'     => $googleUser->getEmail(),
            'password'  => Hash::make(Str::password(32)),
        ]]);

        return redirect()->route('auth.complete');
    }

    public function showCompleteForm()
    {
        if (! session()->has('oauth')) {
            return redirect()->route('register');
        }

        return view('auth.complete');
    }

    public function completeRegistration(Request $request)
    {
        $oauth = session('oauth');

        if (! $oauth) {
            return redirect()->route('register');
        }

        $data = $request->validate([
            'programme' => ['required', 'string', 'max:120'],
            'year'      => ['required', 'integer', 'between:1,6'],
        ]);

        $user = User::create([
            'firstname'  => $oauth['firstname'],
            'lastname'   => $oauth['lastname'],
            'email'      => $oauth['email'],
            'password'   => $oauth['password'],
            'programme'  => $data['programme'],
            'year'       => $data['year'],
        ]);

        session()->forget('oauth');

        Auth::login($user);
        return redirect()->route('dashboard');
    }
}
