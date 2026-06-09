@extends('layouts.app')
@section('title','Login — MUST CSIT Society')
@section('content')
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-card-inner">
      <div class="auth-logo">
        @if(file_exists(public_path('images/csit-logo.jpg')))
          <img src="{{ asset('images/csit-logo.jpg') }}" alt="CSIT Society">
        @else
          <span>CS</span>
        @endif
      </div>
      <h1>Welcome back</h1>
      <p class="sub">Sign in to the CSIT Society portal</p>
    </div>

    @if($errors->any())
      <div class="errors" role="alert">
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    @if(session('success'))
      <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" aria-label="Login form">
      @csrf
      <div class="form-row">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="you@must.ac.mw">
      </div>
      <div class="form-row">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
      </div>
      <div class="form-meta">
        <label>
          <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
          <span>Remember me</span>
        </label>
        <a href="#" aria-label="Forgot your password?">Forgot password?</a>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </form>

    <div class="oauth-divider" role="separator" aria-label="or"><span>or</span></div>

    <a href="{{ route('auth.google') }}" class="btn btn-google btn-block">
      <svg width="18" height="18" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.54 28.59A14.5 14.5 0 0 1 9.5 24c0-1.59.28-3.14.76-4.59l-7.98-6.19A23.99 23.99 0 0 0 0 24c0 3.77.87 7.35 2.56 10.56l7.98-5.97z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 5.97C6.51 42.62 14.62 48 24 48z"/></svg>
      Sign in with Google
    </a>

    <p class="auth-foot">Not a member yet? <a href="{{ route('register') }}">Join the Society</a></p>
  </div>
</div>
@endsection
