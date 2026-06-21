@extends('layouts.dashboard')
@section('title', $poll->question . ' — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>{{ $poll->question }}</h1>
    @if($poll->description)
      <p>{{ $poll->description }}</p>
    @endif
  </div>
</div>

@if($errors->any())
  <div class="alert alert-error" role="alert">{{ $errors->first() }}</div>
@endif

@if($userVote)
  <div class="dash-card">
    <h3 style="margin-bottom:16px">Results</h3>
    @foreach($results as $r)
      <div style="margin-bottom:12px">
        <div style="display:flex;justify-content:space-between;font-size:0.9rem">
          <span>{{ $r['option']->label }}</span>
          <span>{{ $r['count'] }} ({{ $r['pct'] }}%)</span>
        </div>
        <div style="margin-top:4px;height:8px;background:var(--border);border-radius:4px;overflow:hidden">
          <div style="height:100%;width:{{ $r['pct'] }}%;background:var(--accent);border-radius:4px"></div>
        </div>
      </div>
    @endforeach
    <p style="color:var(--ink-secondary);font-size:0.85rem;margin-top:12px">You voted in this poll.</p>
  </div>

@elseif($poll->isActive())
  <div class="dash-card">
    <form method="POST" action="{{ route('polls.vote', $poll) }}">
      @csrf
      <div style="display:flex;flex-direction:column;gap:10px">
        @foreach($poll->options as $option)
          <label style="display:flex;align-items:center;gap:10px;padding:10px;border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer">
            <input type="radio" name="poll_option_id" value="{{ $option->id }}" required>
            <span>{{ $option->label }}</span>
          </label>
        @endforeach
      </div>
      <button class="btn btn-primary" type="submit" style="margin-top:16px">Submit Vote</button>
    </form>
  </div>
@else
  <div class="dash-card">
    <h3 style="margin-bottom:16px">Final Results</h3>
    @foreach($results as $r)
      <div style="margin-bottom:12px">
        <div style="display:flex;justify-content:space-between;font-size:0.9rem">
          <span>{{ $r['option']->label }}</span>
          <span>{{ $r['count'] }} ({{ $r['pct'] }}%)</span>
        </div>
        <div style="margin-top:4px;height:8px;background:var(--border);border-radius:4px;overflow:hidden">
          <div style="height:100%;width:{{ $r['pct'] }}%;background:var(--accent);border-radius:4px"></div>
        </div>
      </div>
    @endforeach
    <p style="color:var(--ink-secondary);font-size:0.85rem;margin-top:12px">This poll is closed.</p>
  </div>
@endif

<p style="margin-top:24px"><a href="{{ route('polls.index') }}">&larr; Back to polls</a></p>

@endsection
