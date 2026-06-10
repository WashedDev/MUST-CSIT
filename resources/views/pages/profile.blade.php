@extends('layouts.dashboard')
@section('title','My Profile — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>My Profile</h1>
    <p>Your CSIT Society membership details.</p>
  </div>
</div>

<div class="profile-grid">
  <div class="profile-card">
    <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->firstname,0,1)) }}{{ strtoupper(substr(auth()->user()->lastname,0,1)) }}</div>
    <h2>{{ auth()->user()->name }}</h2>
    <div class="role">{{ auth()->user()->isAdmin() ? 'Admin' : 'Member' }} &middot; Year {{ auth()->user()->year }}</div>
    <div style="margin-top:18px">
      <a href="#" class="btn btn-outline btn-block">Edit Profile</a>
    </div>
  </div>

  <div class="info-list">
    <div class="row">
      <div class="k">Reg. Number</div>
      <div class="v">{{ auth()->user()->reg_number }}</div>
    </div>
    <div class="row">
      <div class="k">Programme</div>
      <div class="v">{{ auth()->user()->programme }}</div>
    </div>
    <div class="row">
      <div class="k">Year of Study</div>
      <div class="v">Year {{ auth()->user()->year }}</div>
    </div>
    <div class="row">
      <div class="k">Email</div>
      <div class="v">{{ auth()->user()->email }}</div>
    </div>
    <div class="row">
      <div class="k">Membership</div>
      <div class="v">
        @if(auth()->user()->membership_paid)
          <span class="tag" style="background:#dcfce7;color:#16A34A">Paid</span>
        @else
          <span class="tag" style="background:#fef9c3;color:#A16207">Unpaid</span>
        @endif
      </div>
    </div>
    <div class="row">
      <div class="k">Member Since</div>
      <div class="v">{{ auth()->user()->created_at?->format('M Y') }}</div>
    </div>
  </div>
</div>

@endsection
