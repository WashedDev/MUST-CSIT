@extends('layouts.dashboard')
@section('title', 'Complete Your Payment — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Complete Your Payment</h1>
    <p>Send the exact amount to the account number below via your bank or mobile money.</p>
  </div>
</div>

@if($errors->any())
  <div class="errors" role="alert">
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="dash-card" style="max-width:520px;margin-top:24px">

  <div style="border-bottom:1px solid var(--border);padding-bottom:16px;margin-bottom:16px">
    <h3>Payment Summary</h3>
    <div style="display:flex;justify-content:space-between;padding:8px 0">
      <span style="color:var(--ink-500)">Item</span>
      <span>{{ $itemLabel }}</span>
    </div>
    <div style="display:flex;justify-content:space-between;padding:8px 0">
      <span style="color:var(--ink-500)">Description</span>
      <span>{{ $description }}</span>
    </div>
    <div style="display:flex;justify-content:space-between;padding:8px 0">
      <span style="color:var(--ink-500)">Amount</span>
      <span><strong>MWK {{ number_format((int) $amount) }}</strong></span>
    </div>
  </div>

  <div style="text-align:center;padding:24px 0">
    <p style="color:var(--ink-500);margin-bottom:8px">Send payment to this account number:</p>
    <div style="font-size:2.5rem;font-weight:800;letter-spacing:4px;color:var(--primary);background:var(--bg-muted);padding:16px;border-radius:12px;font-family:monospace">
      {{ $tanNumber }}
    </div>
    <p style="font-size:0.85rem;color:var(--ink-400);margin-top:12px">
      Expires at {{ \Carbon\Carbon::parse($tanExpiry)->format('H:i') }} (15 minutes)
    </p>
  </div>

  <div style="background:#FEF3C7;border-radius:8px;padding:12px 16px;font-size:0.85rem;color:#92400E;margin-bottom:20px">
    <strong>Instructions:</strong> Open your bank or mobile money app, send <strong>MWK {{ number_format((int) $amount) }}</strong> to account number <strong>{{ $tanNumber }}</strong>. Once done, click the button below to verify.
  </div>

  <form method="POST" action="{{ route('payment.tan.check', $payment->id) }}">
    @csrf
    <button type="submit" class="btn btn-primary btn-block" style="padding:14px">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;vertical-align:middle"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      I've Made the Payment — Check Status
    </button>
  </form>

  <p style="text-align:center;font-size:0.8rem;color:var(--ink-400);margin-top:16px">
    Powered by OneKhusa Payment Gateway
  </p>
</div>

@push('scripts')
<script>
  var expiryTime = new Date(@json($tanExpiry)).getTime();
  function updateCountdown() {
    var now = new Date().getTime();
    var diff = expiryTime - now;
    if (diff <= 0) {
      document.getElementById('countdown').innerHTML = 'Expired';
      return;
    }
    var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((diff % (1000 * 60)) / 1000);
    document.getElementById('countdown').innerHTML = minutes + 'm ' + seconds + 's';
  }
  setInterval(updateCountdown, 1000);
  updateCountdown();
</script>
@endpush

@endsection
