@extends('layouts.dashboard')
@section('title', 'Manage Events — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Manage Events</h1>
    <p>{{ $events->total() }} event(s).</p>
  </div>
  <a href="{{ route('admin.events.create') }}" class="btn btn-primary">Create Event</a>
</div>

@if(session('success'))
  <div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif

<div class="dash-card" style="padding:0">
  <div class="dash-table-wrap">
    <table class="dash-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Date</th>
          <th>Location</th>
          <th>Capacity</th>
          <th>Bookings</th>
          <th>Price</th>
          <th>Tag</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($events as $e)
          <tr>
            <td><strong>{{ $e->title }}</strong></td>
            <td>{{ $e->date->format('M d, Y H:i') }}</td>
            <td>{{ $e->location }}</td>
            <td>{{ $e->capacity }}</td>
            <td>{{ $e->bookings_count }}</td>
            <td>{{ $e->isPaid() ? 'MWK ' . number_format((float) $e->price) : 'Free' }}</td>
            <td>{{ $e->tag ?? '&mdash;' }}</td>
            <td class="cell-actions">
              <a href="{{ route('admin.events.edit', $e) }}" class="btn btn-primary btn-sm">Edit</a>
              <form method="POST" action="{{ route('admin.events.destroy', $e) }}" style="display:inline" onsubmit="return confirm('Delete this event?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div style="margin-top:16px">{{ $events->links() }}</div>

@endsection
