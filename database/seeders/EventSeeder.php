<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::create([
            'title'       => 'Intro to AI Workshop',
            'description' => 'A hands-on workshop exploring the fundamentals of Artificial Intelligence and Machine Learning.',
            'date'        => now()->addDays(5),
            'location'    => 'MUST Lab A',
            'capacity'    => 40,
            'tag'         => 'Workshop',
        ]);

        Event::create([
            'title'       => 'CSIT Hackathon 2026',
            'description' => 'A 48-hour hackathon where teams build innovative solutions to real-world problems.',
            'date'        => now()->addDays(27),
            'location'    => 'Main Hall',
            'capacity'    => 100,
            'tag'         => 'Hackathon',
        ]);

        Event::create([
            'title'       => 'Cybersecurity Talk',
            'description' => 'Industry experts discuss the latest trends and threats in cybersecurity.',
            'date'        => now()->addDays(45),
            'location'    => 'Auditorium',
            'capacity'    => 80,
            'tag'         => 'Talk',
        ]);

        Event::create([
            'title'       => 'Open Source Day',
            'description' => 'A day dedicated to open-source contributions, workshops, and community building.',
            'date'        => now()->addDays(60),
            'location'    => 'Lab B',
            'capacity'    => 30,
            'tag'         => 'Community',
        ]);
    }
}
