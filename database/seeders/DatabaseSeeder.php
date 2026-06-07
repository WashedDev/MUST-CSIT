<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'firstname' => 'Admin',
            'lastname'  => 'User',
            'email'     => 'admin@must.ac.mw',
        ]);

        User::factory()->create([
            'firstname' => 'John',
            'lastname'  => 'Doe',
            'email'     => 'member@must.ac.mw',
        ]);

        User::factory(8)->create();

        $this->call([
            ElectionSeeder::class,
            EventSeeder::class,
            ArticleSeeder::class,
            DocumentSeeder::class,
        ]);
    }
}
