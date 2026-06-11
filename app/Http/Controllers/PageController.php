<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Election;
use App\Models\Event;
use App\Models\User;

class PageController extends Controller
{
    public function landing()
    {
        $events = null;
        if (auth()->check()) {
            $events = Event::where('date', '>=', now())
                ->withExists(['bookings as user_booked' => fn($q) => $q->where('user_id', auth()->id())])
                ->orderByDesc('user_booked')
                ->orderBy('date')
                ->limit(6)
                ->get();
        }
        return view('pages.landing', compact('events'));
    }

    public function dashboard()
    {
        $user = auth()->user();

        $upcomingEvents = Event::where('date', '>=', now())
            ->orderBy('date')->limit(3)->get();

        $latestArticles = Article::latest('published_at')
            ->with('author')->limit(3)->get();

        if ($user->isAdmin()) {
            $stats = [
                'members'          => User::count(),
                'upcoming_events'  => Event::where('date', '>=', now())->count(),
                'active_elections' => Election::where('status', 'active')->count(),
                'articles'         => Article::count(),
            ];

            $recentMembers = User::latest()->limit(5)->get();

            return view('pages.dashboard-admin', compact('stats', 'upcomingEvents', 'latestArticles', 'recentMembers'));
        }

        $myBookings = $user->bookings()->with('event')->latest()->limit(3)->get();

        $stats = [
            'my_bookings'      => $user->bookings()->count(),
            'upcoming_events'  => Event::where('date', '>=', now())->count(),
            'active_elections' => Election::where('status', 'active')->count(),
            'new_articles'     => Article::where('published_at', '>=', now()->subMonth())->count(),
        ];

        return view('pages.dashboard', compact('stats', 'upcomingEvents', 'latestArticles', 'myBookings'));
    }

    public function toggleLayout()
    {
        if (auth()->user()->isAdmin()) {
            session()->forget('layout');
            return back();
        }

        $current = session('layout', 'sidebar');
        session(['layout' => $current === 'sidebar' ? 'topbar' : 'sidebar']);

        return back();
    }

    public function profile()
    {
        return view('pages.profile');
    }
}
