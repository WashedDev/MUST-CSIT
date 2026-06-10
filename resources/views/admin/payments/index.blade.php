@extends('layouts.dashboard')
@section('title', 'Manage Payments — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Manage Payments</h1>
    <p>{{ $paidCount }} paid &middot; {{ $unpaidCount }} unpaid</p>
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
          <th>Member</th>
          <th>Amount</th>
          <th>Gateway</th>
          <th>Reference</th>
          <th>Status</th>
          <th>Paid At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($payments as $p)
          <tr>
            <td><strong>{{ $p->user->name }}</strong><br><span style="font-size:0.8rem;color:var(--ink-400)">{{ $p->user->email }}</span></td>
            <td>{{ $p->currency }} {{ number_format((int) $p->amount) }}</td>
            <td>{{ $p->gateway ?? '&mdash;' }}</td>
            <td style="font-size:0.8rem">{{ $p->gateway_reference ?? '&mdash;' }}</td>
            <td>
              @if($p->status === 'completed')
                <span class="tag" style="background:#dcfce7;color:#16A34A">Paid</span>
              @elseif($p->status === 'pending')
                <span class="tag" style="background:#fef9c3;color:#A16207">Pending</span>
              @else
                <span class="tag" style="background:#fef2f2;color:#DC2626">Failed</span>
              @endif
            </td>
            <td>{{ $p->paid_at?->format('M d, Y H:i') ?? '&mdash;' }}</td>
            <td class="cell-actions">
              @if($p->status !== 'completed')
                <form method="POST" action="{{ route('admin.payments.mark-paid', $p) }}" style="display:inline">
                  @csrf @method('PUT')
                  <button class="btn btn-primary btn-sm">Mark Paid</button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align:center;padding:32px;color:var(--ink-400)">No payments recorded yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div style="margin-top:16px">{{ $payments->links() }}</div>

@endsection
