@extends('layouts.dashboard')
@section('title', 'Vote Receipt — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Vote Receipt</h1>
    <p>{{ $election->title }}</p>
  </div>
</div>

<div class="dash-card" style="max-width:560px;text-align:center">
  <div style="font-size:3rem;margin-bottom:8px">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
  </div>

  <h2 style="margin-bottom:4px">Your vote has been recorded</h2>
  <p style="color:var(--ink-secondary);margin-bottom:20px">
    Save this receipt to verify your vote later.
  </p>

  <div style="background:var(--surface-alt);padding:16px;border-radius:var(--radius-sm);margin-bottom:20px;word-break:break-all;font-family:monospace;font-size:0.85rem">
    {{ $receipt }}
  </div>

  <div style="text-align:left;margin-bottom:20px">
    <strong style="font-size:0.85rem;color:var(--ink-secondary)">Your vote(s):</strong>
    <ul style="margin:8px 0 0;padding-left:20px">
      @forelse($candidates as $candidate)
        <li style="margin:4px 0">{{ $candidate->user->name }} — {{ $candidate->position }}</li>
      @empty
        <li style="color:var(--ink-secondary)">(no candidates listed)</li>
      @endforelse
    </ul>
  </div>

  <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap">
    <a href="{{ route('elections.verify', $election) }}" class="btn btn-primary">Verify Receipt</a>
    <a href="{{ route('elections.show', $election) }}" class="btn btn-outline">Back to Election</a>
  </div>
</div>

<p style="margin-top:24px;font-size:0.85rem;color:var(--ink-secondary)">
  Your receipt is a cryptographic hash of your vote. No one can identify you from it.
</p>

@endsection
