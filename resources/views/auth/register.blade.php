@extends('layouts.app')
@section('title','Register — MUST CSIT Society')
@section('content')
<div class="auth-page">
  <div class="auth-card auth-card-wide">
    <div class="auth-card-inner">
      <div class="auth-logo">
        @if(file_exists(public_path('images/csit-logo.jpg')))
          <img src="{{ asset('images/csit-logo.jpg') }}" alt="CSIT Society">
        @else
          <span>CS</span>
        @endif
      </div>
      <h1>Join CSIT Society</h1>
      <p class="sub">Open to all MUST students in Computer Science or IT</p>
    </div>

    @if($errors->any())
      <div class="errors" role="alert">
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div class="form-grid">
        <div class="form-row">
          <label for="firstname">First Name</label>
          <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" required>
        </div>
        <div class="form-row">
          <label for="lastname">Last Name</label>
          <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
        </div>
      </div>
      <div class="form-grid">
        <div class="form-row">
          <label for="reg_number">Registration No.</label>
          <input type="text" id="reg_number" name="reg_number" value="{{ old('reg_number') }}" placeholder="e.g. BIT/22/SC/0001" required>
        </div>
        <div class="form-row">
          <label for="year">Year of Study</label>
          <select id="year" name="year" required>
            <option value="">Select&hellip;</option>
            @for($i=1;$i<=6;$i++)<option value="{{$i}}" @selected(old('year')==$i)>Year {{$i}}</option>@endfor
          </select>
        </div>
      </div>
      <div class="form-row">
        <label for="programme">Programme</label>
        <input type="text" id="programme" name="programme" value="{{ old('programme') }}" placeholder="e.g. BSc Computer Science" required>
      </div>
      <div class="form-row">
        <label for="email">University Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@must.ac.mw" required>
      </div>
      <div class="form-grid">
        <div class="form-row">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        <div class="form-row">
          <label for="password_confirmation">Confirm Password</label>
          <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
      </div>
      <button type="submit" class="btn btn-accent btn-block">Create Account</button>
    </form>

    <div class="oauth-divider" role="separator" aria-label="or"><span>or</span></div>

    <a href="{{ route('auth.google') }}" class="btn btn-google btn-block">
      <svg width="18" height="18" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.54 28.59A14.5 14.5 0 0 1 9.5 24c0-1.59.28-3.14.76-4.59l-7.98-6.19A23.99 23.99 0 0 0 0 24c0 3.77.87 7.35 2.56 10.56l7.98-5.97z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 5.97C6.51 42.62 14.62 48 24 48z"/></svg>
      Sign up with Google
    </a>

    <p class="auth-foot">Already a member? <a href="{{ route('login') }}">Sign in</a></p>
  </div>
</div>
@endsection
