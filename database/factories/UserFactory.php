<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'firstname'          => fake()->firstName(),
            'lastname'           => fake()->lastName(),
            'email'              => fake()->unique()->userName() . '@must.ac.mw',
            'email_verified_at'  => now(),
            'password'           => static::$password ??= Hash::make('password'),
            'reg_number'         => 'CSIT/' . fake()->year() . '/' . fake()->unique()->randomNumber(4),
            'programme'          => fake()->randomElement(['Computer Science', 'Information Technology', 'Software Engineering']),
            'year'               => (string) fake()->numberBetween(1, 4),
            'role'               => 'member',
            'membership_paid'    => true,
            'remember_token'     => Str::random(10),
        ];
    }

    public function unpaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'membership_paid' => false,
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}
