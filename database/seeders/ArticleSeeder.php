<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('role', 'admin')->first() ?? User::first();

        Article::create([
            'title'        => 'Welcome to the 2026/27 Academic Year',
            'body'         => 'The CSIT Society welcomes all new and returning students to the 2026/27 academic year. We have an exciting lineup of events, workshops, and hackathons planned. Make sure to register for upcoming events and participate in the society elections.',
            'type'         => 'news',
            'user_id'      => $author->id,
            'published_at' => now(),
        ]);

        Article::create([
            'title'        => 'Getting Started with Laravel',
            'body'         => 'Laravel is a powerful PHP framework that makes web development elegant and enjoyable. In this article, we explore the basics of routing, controllers, and Eloquent ORM. Whether you are a beginner or an experienced developer, Laravel has something to offer.',
            'type'         => 'tech',
            'user_id'      => $author->id,
            'published_at' => now()->subDays(2),
        ]);

        Article::create([
            'title'        => 'Hackathon Registrations Now Open',
            'body'         => 'Registrations for the CSIT Hackathon 2026 are now open! Form your team of 3–5 members and get ready for an exciting 48-hour coding challenge. Prizes include internships, cash awards, and mentorship opportunities.',
            'type'         => 'news',
            'user_id'      => $author->id,
            'published_at' => now()->subDays(5),
        ]);

        Article::create([
            'title'        => 'Annual General Meeting – Save the Date',
            'body'         => 'The CSIT Society Annual General Meeting will be held on 15 July 2026. All members are encouraged to attend. The agenda includes the annual report, financial statements, and the election of new executive committee members.',
            'type'         => 'announcement',
            'user_id'      => $author->id,
            'published_at' => now()->subDays(3),
        ]);

        Article::create([
            'title'        => 'Tech Talk: AI in Healthcare',
            'body'         => 'Join us for a tech talk on "Artificial Intelligence in Healthcare" presented by Dr. Kamanga. The event will cover machine learning applications in medical diagnosis, patient care, and health informatics.',
            'type'         => 'event',
            'user_id'      => $author->id,
            'published_at' => now()->subDay(),
        ]);
    }
}
