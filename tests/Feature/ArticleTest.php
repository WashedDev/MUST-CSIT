<?php

use App\Models\Article;
use App\Models\User;

beforeEach(function () {
    $this->member = User::factory()->create();
});

it('lists articles', function () {
    Article::factory()->create(['user_id' => $this->member->id]);

    $this->actingAs($this->member)
        ->get(route('articles.index'))
        ->assertOk();
});

it('shows a single article', function () {
    $article = Article::factory()->create(['user_id' => $this->member->id]);

    $this->actingAs($this->member)
        ->get(route('articles.show', $article))
        ->assertOk()
        ->assertSee($article->title);
});

it('allows members to create articles', function () {
    $this->actingAs($this->member)
        ->post(route('articles.store'), [
            'title' => 'Test Article',
            'body'  => 'Article body content here.',
            'type'  => 'news',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('articles', ['title' => 'Test Article']);
});
