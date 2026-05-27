@extends('layouts.app')
@section('title','Login — MUST CSIT Society')
@section('content')
<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="logo" style="width:70px;height:70px;border-radius:50%;background:var(--must-blue);color:#fff;display:flex;align-items:center;justify-content:center;font-family:Georgia,serif;font-weight:700;border:3px solid var(--must-red);overflow:hidden">
        @if(file_exists(public_path('images/csit-logo.jpg')))
          <img src="{{ asset('images/csit-logo.jpg') }}" alt="CSIT" style="width:100%;height:100%;border-radius:50%;object-fit:cover">
        @else CSIT @endif
      </div>
    </div>
    <h1>Member Login</h1>
    <p class="sub">Sign in to the CSIT Society portal</p>

    @if($errors->any())
      <div class="errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('login') }}">@csrf
      <div class="form-row">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
      </div>
      <div class="form-row">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>
      <div class="form-meta">
        <label><input type="checkbox" name="remember"> Remember me</label>
        <a href="#">Forgot password?</a>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </form>

    <div class="oauth-divider"><span>or</span></div>

    <a href="{{ route('auth.google') }}" class="btn btn-google btn-block">Sign in with Google</a>

    <p class="auth-foot">Not a member yet? <a href="{{ route('register') }}">Join the Society</a></p>
  </div>
</div>
@endsection
