@extends('layouts.dashboard')
@section('title', 'Settings — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Settings</h1>
  </div>
</div>

<div class="dash-card" style="max-width:540px">
  <h2 style="margin-bottom:12px">Session Timeout</h2>
  <p style="font-size:0.85rem;color:var(--ink-secondary);margin-bottom:16px">
    Control how long a user can remain idle before being logged out.
  </p>

  <form method="POST" action="{{ route('admin.settings.session-lifetime') }}">
    @csrf
    <div class="form-group">
      <label for="session_lifetime">Session Lifetime (minutes)</label>
      <input type="number" id="session_lifetime" name="session_lifetime" value="{{ $session_lifetime }}" min="5" max="1440" required>
      @error('session_lifetime') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <button class="btn btn-primary" type="submit">Save</button>
  </form>
</div>

@endsection
