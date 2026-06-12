<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! str_ends_with($data['email'], '@must.ac.mw')) {
            return back()->withErrors(['email' => 'Only @must.ac.mw emails are allowed.'])->onlyInput('email');
        }

        if (! Auth::attempt($data, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        if (! auth()->user()->onboarding_completed) {
            return redirect()->intended(route('onboarding.index'));
        }
        return redirect()->intended(route('dashboard'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'firstname'  => ['required', 'string', 'max:60'],
            'lastname'   => ['required', 'string', 'max:60'],
            'reg_number'  => ['required', 'string', 'max:40', 'unique:users,reg_number'],
            'programme'   => ['required', 'string', 'max:120'],
            'year'        => ['required', 'integer', 'between:1,6'],
            'email'       => ['required', 'email', 'max:180', 'unique:users,email', 'ends_with:must.ac.mw'],
            'password'    => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'firstname'  => $data['firstname'],
            'lastname'   => $data['lastname'],
            'reg_number' => $data['reg_number'],
            'programme'  => $data['programme'],
            'year'       => $data['year'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
        ]);

        Auth::login($user);
        return redirect()->route('payment.show');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing');
    }
}
