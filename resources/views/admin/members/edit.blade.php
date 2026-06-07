@extends('layouts.dashboard')
@section('title', 'Edit Member — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <h1>Edit Member — {{ $member->name }}</h1>
</div>

<div class="dash-card" style="max-width:540px">
  <form method="POST" action="{{ route('admin.members.update', $member) }}">
    @csrf @method('PUT')

    <div class="form-group">
      <label>First Name</label>
      <input type="text" name="firstname" value="{{ old('firstname', $member->firstname) }}" required>
    </div>

    <div class="form-group">
      <label>Last Name</label>
      <input type="text" name="lastname" value="{{ old('lastname', $member->lastname) }}" required>
    </div>

    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email', $member->email) }}" required>
    </div>

    <div class="form-group">
      <label>Registration Number</label>
      <input type="text" name="reg_number" value="{{ old('reg_number', $member->reg_number) }}">
    </div>

    <div class="form-group">
      <label>Programme</label>
      <input type="text" name="programme" value="{{ old('programme', $member->programme) }}">
    </div>

    <div class="form-group">
      <label>Year</label>
      <input type="text" name="year" value="{{ old('year', $member->year) }}">
    </div>

    <button class="btn btn-primary" type="submit">Update</button>
    <a href="{{ route('admin.members.index') }}" class="btn btn-outline">Cancel</a>
  </form>
</div>

@endsection
