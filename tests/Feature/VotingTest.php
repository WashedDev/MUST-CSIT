<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\User;
use App\Models\Vote;

beforeEach(function () {
    $this->member = User::factory()->create();
    $this->admin  = User::factory()->admin()->create();

    $this->election = Election::factory()->create([
        'starts_at' => now()->subDay(),
        'ends_at'   => now()->addDays(6),
        'status'    => 'active',
    ]);

    $this->candidate = Candidate::factory()->create([
        'election_id' => $this->election->id,
    ]);
});

it('allows a member to view elections', function () {
    $this->actingAs($this->member)
        ->get(route('elections.index'))
        ->assertOk();
});

it('allows a member to cast a vote', function () {
    $this->actingAs($this->member)
        ->post(route('elections.vote', $this->election), [
            'candidate_id' => $this->candidate->id,
        ])
        ->assertRedirect();

    expect(Vote::where('election_id', $this->election->id)
        ->where('user_id', $this->member->id)
        ->exists())->toBeTrue();
});

it('prevents duplicate voting', function () {
    Vote::create([
        'election_id'  => $this->election->id,
        'user_id'      => $this->member->id,
        'candidate_id' => $this->candidate->id,
    ]);

    $this->actingAs($this->member)
        ->post(route('elections.vote', $this->election), [
            'candidate_id' => $this->candidate->id,
        ])
        ->assertSessionHasErrors();
});

it('blocks voting on inactive elections', function () {
    $this->election->update(['status' => 'closed']);

    $this->actingAs($this->member)
        ->post(route('elections.vote', $this->election), [
            'candidate_id' => $this->candidate->id,
        ])
        ->assertSessionHasErrors();
});

it('shows election results', function () {
    $this->actingAs($this->member)
        ->get(route('elections.results', $this->election))
        ->assertOk();
});
