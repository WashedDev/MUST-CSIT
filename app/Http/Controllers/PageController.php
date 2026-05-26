<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function landing()
    {
        return view('pages.landing');
    }

    public function dashboard()
    {
        $stats = [
            'members'        => 248,
            'upcoming_events'=> 4,
            'projects'       => 12,
            'workshops'      => 6,
        ];
        return view('pages.dashboard', compact('stats'));
    }

    public function events()
    {
        $events = [
            ['title' => 'Intro to AI Workshop', 'date' => 'Jun 12, 2026', 'location' => 'MUST Lab A', 'tag' => 'Workshop'],
            ['title' => 'CSIT Hackathon 2026',  'date' => 'Jul 04, 2026', 'location' => 'Main Hall',  'tag' => 'Hackathon'],
            ['title' => 'Cybersecurity Talk',   'date' => 'Aug 21, 2026', 'location' => 'Auditorium', 'tag' => 'Talk'],
            ['title' => 'Open Source Day',      'date' => 'Sep 30, 2026', 'location' => 'Lab B',      'tag' => 'Community'],
        ];
        return view('pages.events', compact('events'));
    }

    public function profile()
    {
        return view('pages.profile');
    }
}
