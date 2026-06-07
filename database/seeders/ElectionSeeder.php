<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\User;
use Illuminate\Database\Seeder;

class ElectionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'member')->get();

        $election = Election::create([
            'title'       => 'CSIT Society President 2026/27',
            'description' => 'Elect the next president of the Computer Science & IT Society for the 2026/27 academic year.',
            'starts_at'   => now()->subDays(1),
            'ends_at'     => now()->addDays(6),
            'status'      => 'active',
        ]);

        $candidates = $users->random(min(3, $users->count()))->map(fn ($u) => Candidate::create([
            'election_id' => $election->id,
            'user_id'     => $u->id,
            'position'    => 'President',
            'manifesto'   => fake()->paragraph(),
        ]));

        Election::create([
            'title'       => 'Constitutional Amendment Vote',
            'description' => 'Vote on the proposed amendments to the CSIT Society constitution.',
            'starts_at'   => now()->addMonth(),
            'ends_at'     => now()->addMonth()->addDays(7),
            'status'      => 'pending',
        ]);
    }
}
