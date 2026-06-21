@extends('layouts.dashboard')
@section('title', 'Create Election — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Create Election</h1>
  </div>
</div>

<div class="dash-card" style="max-width:540px">
  <form method="POST" action="{{ route('admin.elections.store') }}">
    @csrf

    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" value="{{ old('title') }}" required>
      @error('title') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="description">Description</label>
      <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
      @error('description') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="election_type">Election Type</label>
      <select id="election_type" name="election_type" required>
        <option value="single" {{ old('election_type') === 'single' ? 'selected' : '' }}>Single-choice (one candidate)</option>
        <option value="multiple" {{ old('election_type') === 'multiple' ? 'selected' : '' }}>Multiple-choice (select many)</option>
      </select>
      @error('election_type') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="eligible_group">Eligible Voters</label>
      <select id="eligible_group" name="eligible_group" required>
        <option value="all" {{ old('eligible_group') === 'all' ? 'selected' : '' }}>All Members</option>
        <option value="member" {{ old('eligible_group') === 'member' ? 'selected' : '' }}>Members Only</option>
        <option value="moderator" {{ old('eligible_group') === 'moderator' ? 'selected' : '' }}>Moderators &amp; Above</option>
        <option value="executive" {{ old('eligible_group') === 'executive' ? 'selected' : '' }}>Executives &amp; Above</option>
        <option value="admin" {{ old('eligible_group') === 'admin' ? 'selected' : '' }}>Admins Only</option>
      </select>
      @error('eligible_group') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="starts_at">Start Date &amp; Time</label>
      <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at') }}" required>
      @error('starts_at') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="ends_at">End Date &amp; Time</label>
      <input type="datetime-local" id="ends_at" name="ends_at" value="{{ old('ends_at') }}" required>
      @error('ends_at') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div style="display:flex;gap:8px">
      <button class="btn btn-primary" type="submit">Create Election</button>
      <a href="{{ route('admin.elections.index') }}" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>

@endsection
