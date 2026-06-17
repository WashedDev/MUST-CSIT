@extends('layouts.dashboard')
@section('title', 'Results: ' . $election->title . ' — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Results &mdash; {{ $election->title }}</h1>
    <p>{{ $totalVotes }} vote(s) cast
      @if($totalEligible > 0)
        &middot; {{ round(($totalVotes / $totalEligible) * 100) }}% turnout
        ({{ $totalEligible }} eligible voters)
      @endif
    </p>
  </div>
</div>

@forelse($results as $candidate)
  @php $pct = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100) : 0; @endphp
  <div class="dash-card" style="margin-bottom:12px">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <div>
        <strong>{{ $candidate->user->name }}</strong>
        <div style="color:var(--ink-secondary);font-size:0.88rem">{{ $candidate->position }}</div>
      </div>
      <div style="text-align:right">
        <div style="font-size:1.3rem;font-weight:700">{{ $candidate->votes_count }}</div>
        <div style="color:var(--ink-secondary);font-size:0.82rem">{{ $pct }}%</div>
      </div>
    </div>
    <div style="margin-top:8px;height:8px;background:var(--border);border-radius:4px;overflow:hidden">
      <div style="height:100%;width:{{ $pct }}%;background:var(--accent);border-radius:4px;transition:width 0.6s ease"></div>
    </div>
  </div>
@empty
  <div class="dash-card">
    <p class="dash-empty">No candidates were found for this election.</p>
  </div>
@endforelse

<div style="margin-top:24px;display:flex;gap:8px">
  <a href="{{ route('elections.show', $election) }}">&larr; Back to election</a>
  <a href="{{ route('elections.results.csv', $election) }}" class="btn btn-outline btn-sm" style="margin-left:auto">Export CSV</a>
</div>

@endsection
