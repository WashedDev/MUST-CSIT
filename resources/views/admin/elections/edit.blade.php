@extends('layouts.dashboard')
@section('title', 'Edit Election — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Edit Election &mdash; {{ $election->title }}</h1>
  </div>
</div>

<div class="dash-card" style="max-width:540px">
  <form method="POST" action="{{ route('admin.elections.update', $election) }}">
    @csrf @method('PUT')

    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" value="{{ old('title', $election->title) }}" required>
      @error('title') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="description">Description</label>
      <textarea id="description" name="description" rows="4">{{ old('description', $election->description) }}</textarea>
      @error('description') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="election_type">Election Type</label>
      <select id="election_type" name="election_type" required>
        <option value="single" {{ old('election_type', $election->election_type) === 'single' ? 'selected' : '' }}>Single-choice (one candidate)</option>
        <option value="multiple" {{ old('election_type', $election->election_type) === 'multiple' ? 'selected' : '' }}>Multiple-choice (select many)</option>
      </select>
      @error('election_type') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="eligible_group">Eligible Voters</label>
      <select id="eligible_group" name="eligible_group" required>
        <option value="all" {{ old('eligible_group', $election->eligible_group) === 'all' ? 'selected' : '' }}>All Members</option>
        <option value="member" {{ old('eligible_group', $election->eligible_group) === 'member' ? 'selected' : '' }}>Members Only</option>
        <option value="moderator" {{ old('eligible_group', $election->eligible_group) === 'moderator' ? 'selected' : '' }}>Moderators &amp; Above</option>
        <option value="executive" {{ old('eligible_group', $election->eligible_group) === 'executive' ? 'selected' : '' }}>Executives &amp; Above</option>
        <option value="admin" {{ old('eligible_group', $election->eligible_group) === 'admin' ? 'selected' : '' }}>Admins Only</option>
      </select>
      @error('eligible_group') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="starts_at">Start Date &amp; Time</label>
      <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at', $election->starts_at->format('Y-m-d\TH:i')) }}" required>
      @error('starts_at') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="ends_at">End Date &amp; Time</label>
      <input type="datetime-local" id="ends_at" name="ends_at" value="{{ old('ends_at', $election->ends_at->format('Y-m-d\TH:i')) }}" required>
      @error('ends_at') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div style="display:flex;gap:8px">
      <button class="btn btn-primary" type="submit">Update</button>
      <a href="{{ route('admin.elections.index') }}" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>

<div class="dash-card" style="max-width:540px;margin-top:16px">
  <h2 style="margin-bottom:12px">Candidates</h2>

  @if($election->candidates->count())
    <table style="width:100%;border-collapse:collapse">
      <thead>
        <tr style="text-align:left;border-bottom:1px solid var(--outline)">
          <th style="padding:6px 8px">Name</th>
          <th style="padding:6px 8px">Position</th>
          <th style="padding:6px 8px;width:80px"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($election->candidates as $candidate)
          <tr style="border-bottom:1px solid var(--outline)">
            <td style="padding:6px 8px">{{ $candidate->user->name }}</td>
            <td style="padding:6px 8px">{{ $candidate->position }}</td>
            <td style="padding:6px 8px">
              <form method="POST" action="{{ route('admin.elections.candidates.remove', [$election, $candidate]) }}" onsubmit="return confirm('Remove this candidate?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" type="submit">Remove</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p class="dash-empty">No candidates added yet.</p>
  @endif

  <hr style="margin:16px 0;border:none;border-top:1px solid var(--outline)">

  <h3 style="margin-bottom:8px">Add Candidate</h3>
  <form method="POST" action="{{ route('admin.elections.candidates.add', $election) }}">
    @csrf
    <div class="form-group">
      <label for="user_id">Member</label>
      <select id="user_id" name="user_id" required>
        <option value="">-- Select Member --</option>
        @foreach($members as $member)
          <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->student_id ?? $member->email }})</option>
        @endforeach
      </select>
      @error('user_id') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
      <label for="position">Position</label>
      <input type="text" id="position" name="position" value="{{ old('position') }}" required maxlength="255">
      @error('position') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
      <label for="manifesto">Manifesto (optional)</label>
      <textarea id="manifesto" name="manifesto" rows="3">{{ old('manifesto') }}</textarea>
      @error('manifesto') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <button class="btn btn-primary" type="submit">Add Candidate</button>
  </form>
</div>

@endsection
