@extends('layouts.dashboard')
@section('title','Edit Profile — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Edit Profile</h1>
    <p>Update your personal details.</p>
  </div>
</div>

<div class="dash-card" style="max-width:520px">
  <form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PUT')

    <div class="form-group">
      <label for="firstname">First Name</label>
      <input id="firstname" name="firstname" type="text" class="form-control @error('firstname') has-error @enderror"
             value="{{ old('firstname', auth()->user()->firstname) }}" required maxlength="60">
      @error('firstname')<div class="field-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
      <label for="lastname">Last Name</label>
      <input id="lastname" name="lastname" type="text" class="form-control @error('lastname') has-error @enderror"
             value="{{ old('lastname', auth()->user()->lastname) }}" required maxlength="60">
      @error('lastname')<div class="field-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
      <label for="programme">Programme</label>
      <input id="programme" name="programme" type="text" class="form-control @error('programme') has-error @enderror"
             value="{{ old('programme', auth()->user()->programme) }}" required maxlength="120">
      @error('programme')<div class="field-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
      <label for="year">Year of Study</label>
      <select id="year" name="year" class="form-control @error('year') has-error @enderror" required>
        @foreach(range(1, 6) as $y)
          <option value="{{ $y }}" {{ (old('year', auth()->user()->year) == $y) ? 'selected' : '' }}>Year {{ $y }}</option>
        @endforeach
      </select>
      @error('year')<div class="field-error">{{ $message }}</div>@enderror
    </div>

    <div style="display:flex;gap:8px;margin-top:24px">
      <button type="submit" class="btn btn-primary">Save Changes</button>
      <a href="{{ route('profile') }}" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>

@endsection