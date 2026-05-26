@extends('layouts.app')
@section('title','Register — MUST CSIT Society')
@section('content')
<div class="auth-wrap">
  <div class="auth-card wide">
    <div class="auth-logo">
      <div class="logo" style="width:70px;height:70px;border-radius:50%;background:var(--must-red);color:var(--must-blue-dark);display:flex;align-items:center;justify-content:center;font-family:Georgia,serif;font-weight:700;border:3px solid var(--must-blue);overflow:hidden">
        @if(file_exists(public_path('images/csit-logo.jpg')))
          <img src="{{ asset('images/csit-logo.jpg') }}" alt="CSIT" style="width:100%;height:100%;border-radius:50%;object-fit:cover">
        @else CSIT @endif
      </div>
    </div>
    <h1>Join CSIT Society</h1>
    <p class="sub">Open to all MUST students in Computer Science or IT</p>

    @if($errors->any())
      <div class="errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('register') }}">@csrf
      <div class="form-row">
        <label>Full Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required>
      </div>
      <div class="form-grid">
        <div class="form-row">
          <label>Registration No.</label>
          <input type="text" name="reg_number" value="{{ old('reg_number') }}" placeholder="e.g. BIT/22/SC/0001" required>
        </div>
        <div class="form-row">
          <label>Year of Study</label>
          <select name="year" required>
            <option value="">Select…</option>
            @for($i=1;$i<=6;$i++)<option value="{{$i}}" @selected(old('year')==$i)>Year {{$i}}</option>@endfor
          </select>
        </div>
      </div>
      <div class="form-row">
        <label>Programme</label>
        <input type="text" name="programme" value="{{ old('programme') }}" placeholder="e.g. BSc Computer Science" required>
      </div>
      <div class="form-row">
        <label>University Email</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="you@must.ac.mw" required>
      </div>
      <div class="form-grid">
        <div class="form-row">
          <label>Password</label>
          <input type="password" name="password" required>
        </div>
        <div class="form-row">
          <label>Confirm Password</label>
          <input type="password" name="password_confirmation" required>
        </div>
      </div>
      <button type="submit" class="btn btn-gold btn-block">Create Account</button>
    </form>

    <p class="auth-foot">Already a member? <a href="{{ route('login') }}">Sign in</a></p>
  </div>
</div>
@endsection
