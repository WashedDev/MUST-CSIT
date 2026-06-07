@extends('layouts.dashboard')
@section('title', 'Manage Members — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <h1>Manage Members</h1>
  <p>{{ $members->total() }} registered member(s).</p>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="dash-card" style="padding:0;overflow-x:auto">
  <table style="width:100%;border-collapse:collapse">
    <thead>
      <tr style="text-align:left;border-bottom:2px solid #e5e7eb">
        <th style="padding:12px 16px">Name</th>
        <th style="padding:12px 16px">Email</th>
        <th style="padding:12px 16px">Reg #</th>
        <th style="padding:12px 16px">Programme</th>
        <th style="padding:12px 16px">Year</th>
        <th style="padding:12px 16px">Joined</th>
        <th style="padding:12px 16px">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($members as $m)
        <tr style="border-bottom:1px solid var(--border)">
          <td style="padding:10px 16px">{{ $m->name }}</td>
          <td style="padding:10px 16px">{{ $m->email }}</td>
          <td style="padding:10px 16px">{{ $m->reg_number ?? '—' }}</td>
          <td style="padding:10px 16px">{{ $m->programme ?? '—' }}</td>
          <td style="padding:10px 16px">{{ $m->year ?? '—' }}</td>
          <td style="padding:10px 16px">{{ $m->created_at->format('M d, Y') }}</td>
          <td style="padding:10px 16px;display:flex;gap:6px">
            <a href="{{ route('admin.members.edit', $m) }}" class="btn btn-primary" style="padding:5px 12px;font-size:.82rem">Edit</a>
            <form method="POST" action="{{ route('admin.members.destroy', $m) }}" style="display:inline" onsubmit="return confirm('Remove this member?')">
              @csrf @method('DELETE')
              <button class="btn btn-outline" style="padding:5px 12px;font-size:.82rem">Remove</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div style="margin-top:16px">{{ $members->links() }}</div>

@endsection
