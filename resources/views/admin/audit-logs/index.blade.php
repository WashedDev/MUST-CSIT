@extends('layouts.dashboard')
@section('title', 'Audit Logs — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Audit Logs</h1>
    <p>{{ $logs->total() }} event(s) recorded.</p>
  </div>
</div>

<form method="GET" style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;align-items:center">
  <select name="event" class="form-control" style="width:auto">
    <option value="">All Events</option>
    @foreach($events as $e)
      <option value="{{ $e }}" {{ request('event') === $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
    @endforeach
  </select>
  <select name="type" class="form-control" style="width:auto">
    <option value="">All Types</option>
    @foreach($types as $t)
      <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
    @endforeach
  </select>
  <button class="btn btn-primary btn-sm" type="submit">Filter</button>
  @if(request('event') || request('type'))
    <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline btn-sm">Clear</a>
  @endif
</form>

<div class="dash-card" style="padding:0">
  <div class="dash-table-wrap">
    <table class="dash-table">
      <thead>
        <tr>
          <th>Time</th>
          <th>User</th>
          <th>Event</th>
          <th>Type</th>
          <th>ID</th>
          <th>IP</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
          <tr>
            <td style="white-space:nowrap;font-size:0.8rem">{{ $log->created_at->format('M d, H:i') }}</td>
            <td>{{ $log->user?->name ?? 'System' }}</td>
            <td><span class="tag">{{ $log->event }}</span></td>
            <td style="font-size:0.85rem">{{ class_basename($log->auditable_type) }}</td>
            <td>{{ $log->auditable_id }}</td>
            <td style="font-size:0.8rem;color:var(--ink-secondary)">{{ $log->ip_address ?? '—' }}</td>
          </tr>
        @empty
          <tr><td colspan="6"><p class="dash-empty">No audit logs found.</p></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div style="margin-top:16px">{{ $logs->links() }}</div>

@endsection
