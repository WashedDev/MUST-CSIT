@extends('layouts.app')
@section('title','Renew Membership — MUST CSIT Society')
@section('content')
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-card-inner">
      <h1>Renew Membership</h1>
      <p class="sub">Pay your membership fee to renew your access.</p>
    </div>

    @if($errors->any())
      <div class="errors" role="alert">
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <div style="padding:16px;background:var(--surface-alt);border-radius:var(--radius-sm);margin-bottom:20px;text-align:center">
      <p style="font-size:1.5rem;font-weight:700">MWK {{ number_format((float) $amount) }}</p>
      <p style="font-size:0.85rem;color:var(--ink-secondary)">Annual Membership Fee</p>
    </div>

    <form method="POST" action="{{ route('membership.renew.process') }}">
      @csrf
      <input type="hidden" name="gateway" value="onekhusa">
      <button type="submit" class="btn btn-primary btn-block">Pay with OneKhusa</button>
    </form>

    <p class="auth-foot"><a href="{{ route('profile') }}">Back to Profile</a></p>
  </div>
</div>
@endsection
