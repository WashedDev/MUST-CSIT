<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'title'       => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'date'        => now()->addDays(fake()->numberBetween(1, 60)),
            'location'    => fake()->randomElement(['MUST Lab A', 'Main Hall', 'Auditorium', 'Lab B']),
            'capacity'    => fake()->numberBetween(20, 100),
            'tag'         => fake()->randomElement(['Workshop', 'Hackathon', 'Talk', 'Community']),
        ];
    }
}
