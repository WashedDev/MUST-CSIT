@extends('layouts.dashboard')
@section('title','Notification Preferences — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Notification Preferences</h1>
    <p>Choose which notifications you want to receive.</p>
  </div>
</div>

<div class="dash-card" style="max-width:540px">
  <form method="POST" action="{{ route('profile.notifications.update') }}">
    @csrf

    @php
      $prefs = auth()->user()->notification_preferences ?? [];
      $allTypes = [
        'new_article'      => 'New Article Published',
        'booking_confirmed' => 'Booking Confirmed',
        'event_reminder'   => 'Event Reminder',
        'payment_received' => 'Payment Received',
        'election_opened'  => 'Election Opened',
      ];
    @endphp

    <div style="display:flex;flex-direction:column;gap:12px">
      @foreach($allTypes as $key => $label)
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:8px 0">
          <input type="checkbox" name="notifications[]" value="{{ $key }}"
            {{ in_array($key, $prefs) || empty($prefs) ? 'checked' : '' }}>
          <span>{{ $label }}</span>
        </label>
      @endforeach
    </div>

    <p style="font-size:0.85rem;color:var(--ink-secondary);margin-top:12px">
      If none are selected, all notifications are enabled by default.
    </p>

    <div style="margin-top:20px;display:flex;gap:8px">
      <button class="btn btn-primary" type="submit">Save Preferences</button>
      <a href="{{ route('profile') }}" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>

@endsection
