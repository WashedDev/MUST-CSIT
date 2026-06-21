<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index', [
            'session_lifetime' => env('SESSION_LIFETIME', 120),
        ]);
    }

    public function updateSessionLifetime(Request $request)
    {
        $data = $request->validate([
            'session_lifetime' => 'required|integer|min:5|max:1440',
        ]);

        $envFile = base_path('.env');
        if (file_exists($envFile)) {
            $content = file_get_contents($envFile);
            if (preg_match('/^SESSION_LIFETIME=/m', $content)) {
                $content = preg_replace('/^SESSION_LIFETIME=.*/m', 'SESSION_LIFETIME=' . $data['session_lifetime'], $content);
            } else {
                $content .= "\nSESSION_LIFETIME=" . $data['session_lifetime'] . "\n";
            }
            file_put_contents($envFile, $content);

            if (function_exists('opcache_reset')) {
                opcache_reset();
            }
        }

        return back()->with('success', 'Session timeout updated to ' . $data['session_lifetime'] . ' minutes.');
    }
}
