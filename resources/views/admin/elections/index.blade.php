@extends('layouts.dashboard')
@section('title', 'Manage Elections — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Manage Elections</h1>
    <p>{{ $elections->total() }} election(s).</p>
  </div>
  <a href="{{ route('admin.elections.create') }}" class="btn btn-primary">Create Election</a>
</div>

<div class="dash-card" style="padding:0">
  <div class="dash-table-wrap">
    <table class="dash-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Status</th>
          <th>Candidates</th>
          <th>Votes</th>
          <th>Starts</th>
          <th>Ends</th>
          <th>Group</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($elections as $e)
          <tr>
            <td><strong>{{ $e->title }}</strong></td>
            <td><span class="tag">{{ ucfirst($e->status) }}</span></td>
            <td>{{ $e->candidates_count }}</td>
            <td>{{ $e->votes_count }}</td>
            <td>{{ $e->starts_at->format('M d, Y H:i') }}</td>
            <td>{{ $e->ends_at->format('M d, Y H:i') }}</td>
            <td>{{ $e->eligible_group ?? 'all' }}</td>
            <td class="cell-actions">
              <a href="{{ route('admin.elections.edit', $e) }}" class="btn btn-primary btn-sm">Edit</a>
              <form method="POST" action="{{ route('admin.elections.status', $e) }}" style="display:inline">
                @csrf
                @if($e->status === 'pending' || $e->status === 'paused')
                  <input type="hidden" name="status" value="active">
                  <button class="btn btn-outline btn-sm">Activate</button>
                @elseif($e->status === 'active')
                  <input type="hidden" name="status" value="closed">
                  <button class="btn btn-outline btn-sm" onclick="return confirm('Close this election?')">Close</button>
                @endif
              </form>
              <form method="POST" action="{{ route('admin.elections.destroy', $e) }}" style="display:inline" onsubmit="return confirm('Delete this election and all votes?')">
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

<div style="margin-top:16px">{{ $elections->links() }}</div>

@endsection
