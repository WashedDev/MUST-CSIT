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
    @include('admin.events._form')
    <div style="display:flex;gap:8px;margin-top:16px">
      <button class="btn btn-primary" type="submit">Create Event</button>
      <a href="{{ route('admin.events.index') }}" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>

@endsection
