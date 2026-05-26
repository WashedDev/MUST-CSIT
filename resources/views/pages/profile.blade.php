@extends('layouts.app')
@section('title','My Profile — MUST CSIT Society')
@section('content')

<div class="page-head">
  <div class="container">
    <h1>My Profile</h1>
    <p>Your CSIT Society membership details.</p>
  </div>
</div>

<section class="block">
  <div class="container">
    <div class="profile-grid">
      <div class="profile-card">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
        <h2>{{ auth()->user()->name }}</h2>
        <div class="role">Member · Year {{ auth()->user()->year }}</div>
        <div style="margin-top:18px"><a href="#" class="btn btn-outline btn-block">Edit Profile</a></div>
      </div>
      <div class="info-list">
        <div class="row"><div class="k">Reg. Number</div><div class="v">{{ auth()->user()->reg_number }}</div></div>
        <div class="row"><div class="k">Programme</div><div class="v">{{ auth()->user()->programme }}</div></div>
        <div class="row"><div class="k">Year of Study</div><div class="v">Year {{ auth()->user()->year }}</div></div>
        <div class="row"><div class="k">Email</div><div class="v">{{ auth()->user()->email }}</div></div>
        <div class="row"><div class="k">Member Since</div><div class="v">{{ auth()->user()->created_at?->format('M Y') }}</div></div>
      </div>
    </div>
  </div>
</section>

@endsection
