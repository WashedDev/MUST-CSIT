@extends('layouts.app')
@section('title','Forgot Password — MUST CSIT Society')
@section('content')
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-card-inner">
      <h1>Forgot Password</h1>
      <p class="sub">Enter your email and we'll send a reset link.</p>
    </div>

    @if($errors->any())
      <div class="errors" role="alert">
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    @if(session('success'))
      <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" aria-label="Forgot password form">
      @csrf
      <div class="form-row">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="you@must.ac.mw">
      </div>
      <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
    </form>

    <p class="auth-foot"><a href="{{ route('login') }}">Back to Login</a></p>
  </div>
</div>
@endsection
