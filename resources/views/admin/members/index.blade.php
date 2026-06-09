@extends('layouts.dashboard')
@section('title', 'Manage Members — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Manage Members</h1>
    <p>{{ $members->total() }} registered member(s).</p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif

<div class="dash-card" style="padding:0">
  <div class="dash-table-wrap">
    <table class="dash-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Reg #</th>
          <th>Programme</th>
          <th>Year</th>
          <th>Joined</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($members as $m)
          <tr>
            <td><strong>{{ $m->name }}</strong></td>
            <td>{{ $m->email }}</td>
            <td>{{ $m->reg_number ?? '&mdash;' }}</td>
            <td>{{ $m->programme ?? '&mdash;' }}</td>
            <td>{{ $m->year ?? '&mdash;' }}</td>
            <td>{{ $m->created_at->format('M d, Y') }}</td>
            <td class="cell-actions">
              <a href="{{ route('admin.members.edit', $m) }}" class="btn btn-primary btn-sm">Edit</a>
              <form method="POST" action="{{ route('admin.members.destroy', $m) }}" style="display:inline" onsubmit="return confirm('Remove this member?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline btn-sm">Remove</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div style="margin-top:16px">{{ $members->links() }}</div>

@endsection
