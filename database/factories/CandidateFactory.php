<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    public function definition(): array
    {
        return [
            'election_id' => Election::factory(),
            'user_id'     => User::factory(),
            'position'    => fake()->randomElement(['President', 'Vice President', 'Secretary', 'Treasurer']),
            'manifesto'   => fake()->paragraph(),
        ];
    }
}
