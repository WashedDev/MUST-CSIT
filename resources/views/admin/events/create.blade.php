@extends('layouts.dashboard')
@section('title', 'Create Event — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Create Event</h1>
  </div>
</div>

<div class="dash-card" style="max-width:640px">
  <form method="POST" action="{{ route('admin.events.store') }}">
    @csrf

    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" value="{{ old('title') }}" required>
      @error('title') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="description">Description</label>
      <textarea id="description" name="description" rows="5">{{ old('description') }}</textarea>
      @error('description') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="date">Date &amp; Time</label>
      <input type="datetime-local" id="date" name="date" value="{{ old('date') }}" required>
      @error('date') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="location">Location</label>
      <input type="text" id="location" name="location" value="{{ old('location') }}" required>
      @error('location') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="capacity">Capacity (leave blank for unlimited)</label>
      <input type="number" id="capacity" name="capacity" value="{{ old('capacity') }}" min="0" placeholder="Unlimited">
      @error('capacity') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="price">Price (leave blank for free)</label>
      <div class="form-row" style="display:flex;gap:0">
        <span style="padding:10px 12px;background:var(--surface-alt);border:1px solid var(--border);border-right:0;border-radius:var(--radius-sm) 0 0 var(--radius-sm);color:var(--ink-500)">MWK</span>
        <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" placeholder="0.00" style="border-radius:0 var(--radius-sm) var(--radius-sm) 0">
      </div>
      @error('price') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="event_type">Event Type</label>
      <select id="event_type" name="event_type" required>
        <option value="in_person" {{ old('event_type') === 'in_person' ? 'selected' : '' }}>In-Person</option>
        <option value="virtual" {{ old('event_type') === 'virtual' ? 'selected' : '' }}>Virtual</option>
        <option value="hybrid" {{ old('event_type') === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
      </select>
      @error('event_type') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="registration_deadline">Registration Deadline (optional)</label>
      <input type="datetime-local" id="registration_deadline" name="registration_deadline" value="{{ old('registration_deadline') }}">
      @error('registration_deadline') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="cancel_deadline">Cancellation Deadline (optional)</label>
      <input type="datetime-local" id="cancel_deadline" name="cancel_deadline" value="{{ old('cancel_deadline') }}">
      @error('cancel_deadline') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="tag">Tag</label>
      <input type="text" id="tag" name="tag" value="{{ old('tag') }}" placeholder="e.g. Workshop, Hackathon, Talk">
      @error('tag') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div style="display:flex;gap:8px">
      <button class="btn btn-primary" type="submit">Create Event</button>
      <a href="{{ route('admin.events.index') }}" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>

@endsection
