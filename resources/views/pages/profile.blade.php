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
      <a href="{{ route('profile.edit') }}" class="btn btn-outline btn-block">Edit Profile</a>
    </div>
    @unless(auth()->user()->isAdmin())
      <div style="margin-top:10px">
        <form method="POST" action="{{ route('layout.toggle') }}">@csrf
          <button class="btn btn-ghost btn-block btn-sm" type="submit" style="display:flex;align-items:center;justify-content:center;gap:6px;width:100%">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="22" height="18" rx="2" ry="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
            @if(session('layout', 'sidebar') === 'sidebar')
              Switch to Topbar
            @else
              Switch to Sidebar
            @endif
          </button>
        </form>
      </div>
    @endunless
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
        @if(auth()->user()->membership_status)
          <span class="tag" style="margin-left:4px">{{ ucfirst(auth()->user()->membership_status) }}</span>
        @endif
      </div>
    </div>
    <div class="row">
      <div class="k">Member Since</div>
      <div class="v">{{ auth()->user()->created_at?->format('M Y') }}</div>
    </div>
  </div>
</div>

@if($bookings->isNotEmpty())
<div class="dash-header" style="margin-top:32px">
  <div class="dash-header-text">
    <h2>Event History</h2>
    <p>Your recent event registrations.</p>
  </div>
</div>

<div style="display:flex;flex-direction:column;gap:8px">
  @foreach($bookings as $booking)
    <div class="dash-card" style="display:flex;justify-content:space-between;align-items:center">
      <div>
        <strong>{{ $booking->event->title }}</strong>
        <div style="color:var(--ink-secondary);font-size:0.88rem">
          {{ $booking->event->date?->format('M d, Y') ?? 'No date' }}
          @if($booking->event->location) &middot; {{ $booking->event->location }} @endif
        </div>
      </div>
      <div>
        @if($booking->status === 'confirmed')
          <span class="tag" style="background:#dcfce7;color:#16A34A">Confirmed</span>
        @elseif($booking->status === 'cancelled')
          <span class="tag" style="background:#fef2f2;color:#DC2625">Cancelled</span>
        @else
          <span class="tag">{{ ucfirst($booking->status) }}</span>
        @endif
      </div>
    </div>
  @endforeach
</div>
@endif

@endsection
