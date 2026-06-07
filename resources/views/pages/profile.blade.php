@extends('layouts.dashboard')
@section('title','My Profile — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <h1>My Profile</h1>
  <p>Your CSIT Society membership details.</p>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:24px">
  <div class="dash-card" style="text-align:center">
    <div style="width:90px;height:90px;border-radius:50%;background:var(--must-blue);color:#fff;display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 14px;font-family:Georgia,serif;border:3px solid var(--must-red)">
      {{ strtoupper(substr(auth()->user()->firstname,0,1)) }}{{ strtoupper(substr(auth()->user()->lastname,0,1)) }}
    </div>
    <h2 style="font-size:1.2rem;margin-bottom:4px">{{ auth()->user()->name }}</h2>
    <div style="color:var(--muted);font-size:.9rem">{{ auth()->user()->isAdmin() ? 'Admin' : 'Member' }} · Year {{ auth()->user()->year }}</div>
    <div style="margin-top:18px"><a href="#" class="btn btn-outline btn-block">Edit Profile</a></div>
  </div>

  <div class="dash-card" style="padding:0">
    <div style="display:grid;grid-template-columns:160px 1fr;padding:16px 22px;border-bottom:1px solid var(--border)">
      <div style="color:var(--muted);font-weight:600;font-size:.85rem;text-transform:uppercase;letter-spacing:.05em">Reg. Number</div>
      <div>{{ auth()->user()->reg_number }}</div>
    </div>
    <div style="display:grid;grid-template-columns:160px 1fr;padding:16px 22px;border-bottom:1px solid var(--border)">
      <div style="color:var(--muted);font-weight:600;font-size:.85rem;text-transform:uppercase;letter-spacing:.05em">Programme</div>
      <div>{{ auth()->user()->programme }}</div>
    </div>
    <div style="display:grid;grid-template-columns:160px 1fr;padding:16px 22px;border-bottom:1px solid var(--border)">
      <div style="color:var(--muted);font-weight:600;font-size:.85rem;text-transform:uppercase;letter-spacing:.05em">Year of Study</div>
      <div>Year {{ auth()->user()->year }}</div>
    </div>
    <div style="display:grid;grid-template-columns:160px 1fr;padding:16px 22px;border-bottom:1px solid var(--border)">
      <div style="color:var(--muted);font-weight:600;font-size:.85rem;text-transform:uppercase;letter-spacing:.05em">Email</div>
      <div>{{ auth()->user()->email }}</div>
    </div>
    <div style="display:grid;grid-template-columns:160px 1fr;padding:16px 22px">
      <div style="color:var(--muted);font-weight:600;font-size:.85rem;text-transform:uppercase;letter-spacing:.05em">Member Since</div>
      <div>{{ auth()->user()->created_at?->format('M Y') }}</div>
    </div>
  </div>
</div>

@endsection
