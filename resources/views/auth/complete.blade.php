@extends('layouts.app')
@section('title','Complete Registration — MUST CSIT Society')
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
      <h1>Almost there!</h1>
      <p class="sub">Just a few more details to complete your account.</p>
    </div>

    <div class="oauth-preview">
      <div><strong>{{ session('oauth')['firstname'] }} {{ session('oauth')['lastname'] }}</strong></div>
      <div style="color:var(--ink-secondary);font-size:0.88rem">{{ session('oauth')['email'] }}</div>
    </div>

    @if($errors->any())
      <div class="errors" role="alert">
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ route('auth.complete.submit') }}">
      @csrf
      <div class="form-row">
        <label for="programme">Programme</label>
        <input type="text" id="programme" name="programme" value="{{ old('programme') }}" placeholder="e.g. BSc Computer Science" required>
      </div>
      <div class="form-row">
        <label for="reg_number">Registration Number</label>
        <input type="text" id="reg_number" name="reg_number" value="{{ old('reg_number') }}" placeholder="e.g. CS/01/21">
      </div>
      <div class="form-row">
        <label for="year">Year of Study</label>
        <select id="year" name="year" required>
          <option value="">Select&hellip;</option>
          @for($i=1;$i<=6;$i++)<option value="{{$i}}" @selected(old('year')==$i)>Year {{$i}}</option>@endfor
        </select>
      </div>
      <button type="submit" class="btn btn-accent btn-block">Complete Registration</button>
    </form>
  </div>
</div>
@endsection
