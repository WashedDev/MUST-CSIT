@extends('layouts.dashboard')
@section('title', 'Edit Event — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Edit Event &mdash; {{ $event->title }}</h1>
  </div>
</div>

<div class="dash-card" style="max-width:640px">
  <form method="POST" action="{{ route('admin.events.update', $event) }}">
    @csrf @method('PUT')
    @include('admin.events._form', ['event' => $event])
    <div style="display:flex;gap:8px;margin-top:16px">
      <button class="btn btn-primary" type="submit">Update Event</button>
      <a href="{{ route('admin.events.index') }}" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>

@endsection
