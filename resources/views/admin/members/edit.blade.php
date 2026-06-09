@extends('layouts.dashboard')
@section('title', 'Edit Member — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Edit Member &mdash; {{ $member->name }}</h1>
  </div>
</div>

<div class="dash-card" style="max-width:540px">
  <form method="POST" action="{{ route('admin.members.update', $member) }}">
    @csrf @method('PUT')

    <div class="form-group">
      <label for="firstname">First Name</label>
      <input type="text" id="firstname" name="firstname" value="{{ old('firstname', $member->firstname) }}" required>
    </div>

    <div class="form-group">
      <label for="lastname">Last Name</label>
      <input type="text" id="lastname" name="lastname" value="{{ old('lastname', $member->lastname) }}" required>
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="{{ old('email', $member->email) }}" required>
    </div>

    <div class="form-group">
      <label for="reg_number">Registration Number</label>
      <input type="text" id="reg_number" name="reg_number" value="{{ old('reg_number', $member->reg_number) }}">
    </div>

    <div class="form-group">
      <label for="programme">Programme</label>
      <input type="text" id="programme" name="programme" value="{{ old('programme', $member->programme) }}">
    </div>

    <div class="form-group">
      <label for="year">Year</label>
      <input type="text" id="year" name="year" value="{{ old('year', $member->year) }}">
    </div>

    <div style="display:flex;gap:8px">
      <button class="btn btn-primary" type="submit">Update Member</button>
      <a href="{{ route('admin.members.index') }}" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>

@endsection
