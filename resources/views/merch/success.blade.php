@extends('layouts.dashboard')
@section('title', 'Order Successful — CSIT Merch')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Order Successful</h1>
  </div>
</div>

<div style="max-width:520px;margin-top:24px;text-align:center;padding:48px 24px;background:var(--surface);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm)">
  <div style="font-size:4rem;margin-bottom:16px;color:#16A34A">
    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
  </div>
  <h2>Thank you for your order!</h2>
  <p style="color:var(--ink-500);margin-top:8px">
    Order reference: <strong>{{ $orderRef }}</strong>
  </p>

  <div style="text-align:left;margin-top:24px;padding:16px;background:var(--surface-alt);border-radius:var(--radius-sm)">
    @foreach($purchases as $p)
      <div style="display:flex;justify-content:space-between;padding:8px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--border)' : '' }}">
        <span>{{ $p->quantity }}x {{ $p->item->name }}</span>
        <span style="font-weight:600">MWK {{ number_format((float) $p->amount) }}</span>
      </div>
    @endforeach
  </div>

  <div style="margin-top:24px;display:flex;gap:8px;justify-content:center">
    <a href="{{ route('merch.index') }}" class="btn btn-primary">Continue Shopping</a>
    <a href="{{ route('dashboard') }}" class="btn btn-outline">Dashboard</a>
  </div>
</div>

@endsection
