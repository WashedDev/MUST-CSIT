@extends('layouts.dashboard')
@section('title', 'Membership Payment — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Membership Payment</h1>
    <p>Pay your membership fee to access all society features.</p>
  </div>
</div>

@if(session('info'))
  <div class="alert alert-success" role="alert">{{ session('info') }}</div>
@endif

@if($errors->any())
  <div class="errors" role="alert">
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<div class="dash-card" style="max-width:480px;margin-top:24px">
  <div style="text-align:center;padding:24px 0">
    <div style="font-size:3rem;font-weight:700;color:var(--primary)">MWK {{ number_format((int) $amount) }}</div>
    <div style="color:var(--ink-500);margin-top:4px">Annual Membership Fee</div>
  </div>

  <div style="border-top:1px solid var(--border);padding:16px 0">
    <div style="display:flex;justify-content:space-between;padding:8px 0">
      <span style="color:var(--ink-500)">Description</span>
      <span>CSIT Society Membership {{ date('Y') }}</span>
    </div>
    <div style="display:flex;justify-content:space-between;padding:8px 0">
      <span style="color:var(--ink-500)">Amount</span>
      <span><strong>MWK {{ number_format((int) $amount) }}</strong></span>
    </div>
  </div>

  <form method="POST" action="{{ route('payment.process') }}" style="margin-top:16px">
    @csrf
    <input type="hidden" name="gateway" value="ctechpay">
    <button type="submit" class="btn btn-primary btn-block" style="padding:14px">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;vertical-align:middle"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
      Pay MWK {{ number_format((int) $amount) }}
    </button>
  </form>

  <p style="text-align:center;font-size:0.8rem;color:var(--ink-400);margin-top:16px">
    Your payment is processed securely via CtechPay.
  </p>
</div>

@endsection
