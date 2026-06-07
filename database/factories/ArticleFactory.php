<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'title'        => fake()->sentence(),
            'body'         => fake()->paragraphs(3, true),
            'type'         => fake()->randomElement(['news', 'tech']),
            'user_id'      => User::factory(),
            'published_at' => now(),
        ];
    }
}
