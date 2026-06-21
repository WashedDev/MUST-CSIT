@extends('layouts.dashboard')
@section('title', 'Verify Vote — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Verify Your Vote</h1>
    <p>{{ $election->title }}</p>
  </div>
</div>

<div class="dash-card" style="max-width:520px">
  @if(isset($verified))
    @if($verified)
      <div style="text-align:center;margin-bottom:20px">
        <div style="font-size:3rem;margin-bottom:8px">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        </div>
        <h2 style="margin-bottom:4px">Vote Verified</h2>
        <p style="color:var(--ink-secondary)">Your vote was counted in this election.</p>
      </div>

      <div style="background:var(--surface-alt);padding:16px;border-radius:var(--radius-sm);margin-bottom:16px">
        <strong style="font-size:0.85rem;color:var(--ink-secondary)">Your vote(s):</strong>
        <ul style="margin:8px 0 0;padding-left:20px">
          @foreach($votes as $v)
            <li style="margin:4px 0">{{ $v->candidate->user->name }} — {{ $v->candidate->position }}</li>
          @endforeach
        </ul>
      </div>
    @else
      <div style="text-align:center;margin-bottom:20px">
        <div style="font-size:3rem;margin-bottom:8px">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#e53e3e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        </div>
        <h2 style="margin-bottom:4px">Not Found</h2>
        <p style="color:var(--ink-secondary)">No vote matches this receipt for this election.</p>
      </div>
    @endif

    <div style="text-align:center">
      <a href="{{ route('elections.verify', $election) }}" class="btn btn-outline">Verify Another</a>
    </div>
  @else
    <form method="POST" action="{{ route('elections.verify.post', $election) }}">
      @csrf
      <div>
        <label class="label" for="receipt">Receipt Hash</label>
        <input class="input" id="receipt" name="receipt" placeholder="Paste your 64-character receipt" required style="font-family:monospace;font-size:0.85rem">
        @error('receipt') <span class="field-error">{{ $message }}</span> @enderror
      </div>
      <button class="btn btn-primary" type="submit" style="margin-top:16px">Verify</button>
    </form>
  @endif
</div>

<p style="margin-top:24px"><a href="{{ route('elections.show', $election) }}">&larr; Back to election</a></p>

@endsection
