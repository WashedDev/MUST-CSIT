@extends('layouts.app')
@section('title','Reset Password — MUST CSIT Society')
@section('content')
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-card-inner">
      <h1>Reset Password</h1>
      <p class="sub">Choose a new password for your account.</p>
    </div>

    @if($errors->any())
      <div class="errors" role="alert">
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" aria-label="Reset password form">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">
      <div class="form-row">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@must.ac.mw">
      </div>
      <div class="form-row">
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="Min. 8 characters">
      </div>
      <div class="form-row">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat password">
      </div>
      <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
    </form>
  </div>
</div>
@endsection
