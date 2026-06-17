<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Election;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

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
            $totalMembers = User::count();
            $paidMembers = User::where('membership_paid', true)->count();
            $newThisMonth = User::where('created_at', '>=', now()->startOfMonth())->count();
            $newThisWeek = User::where('created_at', '>=', now()->startOfWeek())->count();

            $stats = [
                'members'          => $totalMembers,
                'paid_members'     => $paidMembers,
                'unpaid_members'   => $totalMembers - $paidMembers,
                'new_this_month'   => $newThisMonth,
                'new_this_week'    => $newThisWeek,
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
        $bookings = auth()->user()->bookings()
            ->with('event')
            ->latest()
            ->limit(10)
            ->get();

        return view('pages.profile', compact('bookings'));
    }

    public function editProfile()
    {
        return view('pages.profile-edit');
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:60',
            'lastname'  => 'required|string|max:60',
            'programme' => 'required|string|max:120',
            'year'      => 'required|integer|between:1,6',
        ]);

        auth()->user()->update($data);

        return redirect()->route('profile')
            ->with('success', 'Profile updated successfully.');
    }
}
