@extends('layouts.dashboard')
@section('title', 'Results: ' . $election->title . ' — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <h1>Results — {{ $election->title }}</h1>
  <p>{{ $totalVotes }} total vote(s) cast.</p>
</div>

@forelse($results as $candidate)
  @php $pct = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100) : 0; @endphp
  <div class="dash-card" style="margin-bottom:12px">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <div>
        <strong>{{ $candidate->user->name }}</strong>
        <div style="color:var(--muted);font-size:.9rem">{{ $candidate->position }}</div>
      </div>
      <div style="text-align:right">
        <div style="font-size:1.4rem;font-weight:700">{{ $candidate->votes_count }}</div>
        <div style="color:var(--muted);font-size:.85rem">{{ $pct }}%</div>
      </div>
    </div>
    <div style="margin-top:8px;height:8px;background:#e5e7eb;border-radius:4px;overflow:hidden">
      <div style="height:100%;width:{{ $pct }}%;background:var(--must-red);border-radius:4px"></div>
    </div>
  </div>
@empty
  <div class="dash-card">
    <p class="dash-empty">No candidates were found for this election.</p>
  </div>
@endforelse

<p style="margin-top:24px"><a href="{{ route('elections.show', $election) }}">← Back to election</a></p>

@endsection
