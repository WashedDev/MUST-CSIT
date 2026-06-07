@extends('layouts.dashboard')
@section('title', $election->title . ' — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <h1>{{ $election->title }}</h1>
  <p>{{ $election->description }}</p>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
  <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<div style="margin-bottom:20px;color:var(--muted)">
  {{ $election->starts_at->format('M d, Y H:i') }} — {{ $election->ends_at->format('M d, Y H:i') }}
  <span class="tag" style="margin-left:10px">{{ ucfirst($election->status) }}</span>
</div>

@if($userVote)
  <div class="dash-card" style="margin-bottom:16px">
    <p style="margin:0">You voted in this election on {{ $userVote->created_at->format('M d, Y H:i') }}.</p>
    <a href="{{ route('elections.results', $election) }}" class="btn btn-primary" style="margin-top:12px">View Results</a>
  </div>

@elseif($election->isActive())
  <h3 style="margin-bottom:12px">Candidates</h3>
  @foreach($candidates as $candidate)
    <div class="dash-card" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
      <div>
        <strong>{{ $candidate->user->name }}</strong>
        <div style="color:var(--muted);font-size:.9rem">{{ $candidate->position }}</div>
        @if($candidate->manifesto)
          <p style="margin:8px 0 0;font-size:.95rem">{{ $candidate->manifesto }}</p>
        @endif
      </div>
      <form method="POST" action="{{ route('elections.vote', $election) }}">
        @csrf
        <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
        <button class="btn btn-primary" type="submit">Vote</button>
      </form>
    </div>
  @endforeach

@else
  <div class="dash-card" style="margin-bottom:16px">
    <p style="margin:0;color:var(--muted)">This election is not open for voting.</p>
    <a href="{{ route('elections.results', $election) }}" class="btn btn-primary" style="margin-top:12px">View Results</a>
  </div>
@endif

<p style="margin-top:24px"><a href="{{ route('events.index') }}">← Back to events</a></p>

@endsection
