<?php

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;

beforeEach(function () {
    $this->member = User::factory()->create();

    $this->event = Event::factory()->create([
        'date'     => now()->addDays(10),
        'capacity' => 2,
    ]);
});

it('lists events', function () {
    $this->actingAs($this->member)
        ->get(route('events.index'))
        ->assertOk();
});

it('shows an event', function () {
    $this->actingAs($this->member)
        ->get(route('events.show', $this->event))
        ->assertOk();
});

it('allows a member to book a seat', function () {
    $this->actingAs($this->member)
        ->post(route('events.book', $this->event))
        ->assertRedirect();

    expect(Booking::where('event_id', $this->event->id)
        ->where('user_id', $this->member->id)
        ->exists())->toBeTrue();
});

it('prevents duplicate bookings', function () {
    Booking::create([
        'event_id' => $this->event->id,
        'user_id'  => $this->member->id,
        'status'   => 'confirmed',
    ]);

    $this->actingAs($this->member)
        ->post(route('events.book', $this->event))
        ->assertSessionHasErrors();
});

it('prevents booking past events', function () {
    $this->event->update(['date' => now()->subDay()]);

    $this->actingAs($this->member)
        ->post(route('events.book', $this->event))
        ->assertSessionHasErrors();
});

it('prevents booking when full', function () {
    Booking::create(['event_id' => $this->event->id, 'user_id' => User::factory()->create()->id, 'status' => 'confirmed']);
    Booking::create(['event_id' => $this->event->id, 'user_id' => User::factory()->create()->id, 'status' => 'confirmed']);

    $this->actingAs($this->member)
        ->post(route('events.book', $this->event))
        ->assertSessionHasErrors();
});

it('allows cancelling a booking', function () {
    Booking::create([
        'event_id' => $this->event->id,
        'user_id'  => $this->member->id,
        'status'   => 'confirmed',
    ]);

    $this->actingAs($this->member)
        ->post(route('events.cancel', $this->event))
        ->assertRedirect();

    expect(Booking::where('event_id', $this->event->id)
        ->where('user_id', $this->member->id)
        ->value('status'))->toBe('cancelled');
});
