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

    <div class="form-group">
      <label for="role">Role</label>
      <select id="role" name="role">
        <option value="member" {{ (old('role', $member->role) === 'member') ? 'selected' : '' }}>Member</option>
        <option value="moderator" {{ (old('role', $member->role) === 'moderator') ? 'selected' : '' }}>Moderator</option>
        <option value="executive" {{ (old('role', $member->role) === 'executive') ? 'selected' : '' }}>Executive</option>
        <option value="admin" {{ (old('role', $member->role) === 'admin') ? 'selected' : '' }}>Admin</option>
      </select>
    </div>

    <div class="form-group">
      <label for="membership_status">Membership Status</label>
      <select id="membership_status" name="membership_status">
        <option value="active" {{ (old('membership_status', $member->membership_status ?? 'active') === 'active') ? 'selected' : '' }}>Active</option>
        <option value="suspended" {{ (old('membership_status', $member->membership_status ?? 'active') === 'suspended') ? 'selected' : '' }}>Suspended</option>
        <option value="expired" {{ (old('membership_status', $member->membership_status ?? 'active') === 'expired') ? 'selected' : '' }}>Expired</option>
        <option value="alumni" {{ (old('membership_status', $member->membership_status ?? 'active') === 'alumni') ? 'selected' : '' }}>Alumni</option>
      </select>
    </div>

    <div style="display:flex;gap:8px">
      <button class="btn btn-primary" type="submit">Update Member</button>
      <a href="{{ route('admin.members.index') }}" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>

@endsection
