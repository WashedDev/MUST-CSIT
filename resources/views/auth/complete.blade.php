@extends('layouts.app')
@section('title','Complete Registration — MUST CSIT Society')
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
    <h1>Almost there!</h1>
    <p class="sub">Just a few more details to complete your account.</p>

    <div class="oauth-preview">
      <div><strong>{{ session('oauth')['firstname'] }} {{ session('oauth')['lastname'] }}</strong></div>
      <div style="color:var(--muted);font-size:.9rem">{{ session('oauth')['email'] }}</div>
    </div>

    @if($errors->any())
      <div class="errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('auth.complete.submit') }}">@csrf
      <div class="form-row">
        <label>Programme</label>
        <input type="text" name="programme" value="{{ old('programme') }}" placeholder="e.g. BSc Computer Science" required>
      </div>
      <div class="form-row">
        <label>Year of Study</label>
        <select name="year" required>
          <option value="">Select…</option>
          @for($i=1;$i<=6;$i++)<option value="{{$i}}" @selected(old('year')==$i)>Year {{$i}}</option>@endfor
        </select>
      </div>
      <button type="submit" class="btn btn-gold btn-block">Complete Registration</button>
    </form>
  </div>
</div>
@endsection
