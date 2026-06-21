@extends('layouts.dashboard')
@section('title', 'Pending Approvals — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Pending Approvals</h1>
    <p>{{ $members->total() }} member(s) awaiting approval.</p>
  </div>
</div>

<div class="dash-card" style="padding:0">
  <div class="dash-table-wrap">
    <table class="dash-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Reg #</th>
          <th>Programme</th>
          <th>Paid</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($members as $m)
          <tr>
            <td><strong>{{ $m->name }}</strong></td>
            <td>{{ $m->email }}</td>
            <td>{{ $m->reg_number ?? '—' }}</td>
            <td>{{ $m->programme ?? '—' }}</td>
            <td>{{ $m->paid_at ? $m->paid_at->format('M d, Y') : '—' }}</td>
            <td class="cell-actions">
              <form method="POST" action="{{ route('admin.members.approve', $m) }}" style="display:inline" class="approve-form" data-name="{{ $m->name }}">
                @csrf
                <button class="btn btn-primary btn-sm">Approve</button>
              </form>
              <form method="POST" action="{{ route('admin.members.reject', $m) }}" style="display:inline" class="reject-form" data-name="{{ $m->name }}">
                @csrf
                <button class="btn btn-danger btn-sm">Reject</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6"><p class="dash-empty">No pending approvals.</p></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div style="margin-top:16px">{{ $members->links() }}</div>

@push('scripts')
<script>
document.querySelectorAll('.approve-form').forEach(function(form) {
  form.addEventListener('submit', function(e) {
    if (!confirm('Approve ' + form.getAttribute('data-name') + '?')) {
      e.preventDefault();
    }
  });
});
document.querySelectorAll('.reject-form').forEach(function(form) {
  form.addEventListener('submit', function(e) {
    if (!confirm('Reject ' + form.getAttribute('data-name') + '?')) {
      e.preventDefault();
    }
  });
});
</script>
@endpush

@endsection
