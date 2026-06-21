@extends('layouts.dashboard')
@section('title', 'Confirm Your Vote — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Confirm Your Vote</h1>
    <p>{{ $election->title }}</p>
  </div>
</div>

<div class="dash-card" style="max-width:520px;text-align:center">
  <div style="font-size:3rem;margin-bottom:8px">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
  </div>

  @if($candidates->count() === 1)
    @php $candidate = $candidates->first(); @endphp
    <h2 style="margin-bottom:4px">{{ $candidate->user->name }}</h2>
    <p style="color:var(--ink-secondary);margin-bottom:24px">{{ $candidate->position }}</p>

    @if($candidate->manifesto)
      <div style="background:var(--surface-alt);padding:16px;border-radius:var(--radius-sm);margin-bottom:24px;text-align:left;font-size:0.92rem">
        <strong style="font-size:0.85rem;color:var(--ink-secondary)">Manifesto:</strong>
        <p style="margin:8px 0 0">{{ $candidate->manifesto }}</p>
      </div>
    @endif
  @else
    <h2 style="margin-bottom:12px">You are voting for:</h2>
    <ul style="text-align:left;display:inline-block;margin-bottom:24px">
      @foreach($candidates as $c)
        <li style="margin:6px 0"><strong>{{ $c->user->name }}</strong> — {{ $c->position }}</li>
      @endforeach
    </ul>
  @endif

  <p style="color:var(--ink-secondary);font-size:0.88rem;margin-bottom:24px">
    Once cast, your vote cannot be changed.
  </p>

  <div style="display:flex;gap:8px;justify-content:center">
    <form method="POST" action="{{ route('elections.vote', $election) }}">
      @csrf
      @if($candidates->count() === 1)
        <input type="hidden" name="candidate_id" value="{{ $candidates->first()->id }}">
      @else
        @foreach($candidates as $c)
          <input type="hidden" name="candidate_ids[]" value="{{ $c->id }}">
        @endforeach
      @endif
      <button class="btn btn-primary" type="submit">Confirm Vote</button>
    </form>
    <a href="{{ route('elections.show', $election) }}" class="btn btn-outline">Go Back</a>
  </div>
</div>

@endsection
