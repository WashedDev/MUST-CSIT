<?php

use App\Models\User;

beforeEach(function () {
    $this->admin  = User::factory()->admin()->create();
    $this->member = User::factory()->create();
});

it('allows admin to view members list', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.members.index'))
        ->assertOk();
});

it('blocks non-admin from members list', function () {
    $this->actingAs($this->member)
        ->get(route('admin.members.index'))
        ->assertForbidden();
});

it('allows admin to edit a member', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.members.edit', $this->member))
        ->assertOk();
});

it('allows admin to update a member', function () {
    $this->actingAs($this->admin)
        ->put(route('admin.members.update', $this->member), [
            'firstname' => 'Updated',
            'lastname'  => 'Name',
            'email'     => $this->member->email,
        ])
        ->assertRedirect();

    expect($this->member->fresh()->firstname)->toBe('Updated');
});
