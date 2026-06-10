@extends('layouts.dashboard')
@section('title', 'Payment Successful — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Payment Successful</h1>
  </div>
</div>

<div class="dash-card" style="max-width:480px;margin-top:24px;text-align:center;padding:48px 24px">
  <div style="font-size:4rem;margin-bottom:16px;color:#16A34A">
    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
  </div>
  <h2>Welcome to CSIT Society!</h2>
  <p style="color:var(--ink-500);margin-top:8px">
    Your membership payment has been received. You now have full access to all society features.
  </p>
  <a href="{{ route('dashboard') }}" class="btn btn-primary" style="margin-top:24px">
    Go to Dashboard
  </a>
</div>

@endsection
