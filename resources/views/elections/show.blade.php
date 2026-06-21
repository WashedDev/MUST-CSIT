@extends('layouts.dashboard')
@section('title', $election->title . ' — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>{{ $election->title }}</h1>
    <p>{{ $election->description }}</p>
  </div>
</div>

@if($errors->any())
  <div class="alert alert-error" role="alert">{{ $errors->first() }}</div>
@endif

<div style="margin-bottom:20px;color:var(--ink-secondary)">
  {{ $election->starts_at->format('M d, Y H:i') }} &mdash; {{ $election->ends_at->format('M d, Y H:i') }}
  <span class="tag" style="margin-left:10px">{{ ucfirst($election->status) }}</span>
  <span class="tag" style="margin-left:4px">{{ $election->election_type === 'multiple' ? 'Multiple-choice' : 'Single-choice' }}</span>
</div>

@if($userVote || $userVotes->isNotEmpty())
  <div class="dash-card" style="margin-bottom:16px">
    <p style="margin:0">You voted in this election.</p>
    <div style="display:flex;gap:8px;margin-top:12px">
      <a href="{{ route('elections.results', $election) }}" class="btn btn-primary">View Results</a>
      <a href="{{ route('elections.verify', $election) }}" class="btn btn-outline">Verify Receipt</a>
    </div>
  </div>

@elseif($election->isActive())
  <h3 style="margin-bottom:12px">Candidates</h3>
  <form method="POST" action="{{ route('elections.confirm', $election) }}" id="voteForm">
    @csrf
    @foreach($candidates as $candidate)
      <div class="dash-card" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;cursor:pointer" onclick="toggleCandidate(this, {{ $candidate->id }})">
        <div style="display:flex;align-items:center;gap:12px">
          @if($election->election_type === 'multiple')
            <input type="checkbox" name="candidate_ids[]" value="{{ $candidate->id }}" class="candidate-checkbox" style="width:18px;height:18px">
          @endif
          <div>
            <strong>{{ $candidate->user->name }}</strong>
            <div style="color:var(--ink-secondary);font-size:0.88rem">{{ $candidate->position }}</div>
            @if($candidate->manifesto)
              <p style="margin:8px 0 0;font-size:0.92rem">{{ $candidate->manifesto }}</p>
            @endif
          </div>
        </div>
        @if($election->election_type !== 'multiple')
          <button class="btn btn-primary" type="submit" name="candidate_id" value="{{ $candidate->id }}">Vote</button>
        @endif
      </div>
    @endforeach

    @if($election->election_type === 'multiple')
      <button class="btn btn-primary" type="submit" id="voteMultipleBtn" disabled>Vote for Selected</button>
    @endif
  </form>

@else
  <div class="dash-card" style="margin-bottom:16px">
    <p style="margin:0;color:var(--ink-secondary)">This election is not open for voting.</p>
    <a href="{{ route('elections.results', $election) }}" class="btn btn-primary" style="margin-top:12px">View Results</a>
  </div>
@endif

<p style="margin-top:24px"><a href="{{ route('elections.index') }}">&larr; Back to elections</a></p>

@push('scripts')
<script>
function toggleCandidate(el, id) {
  var cb = el.querySelector('.candidate-checkbox');
  if (cb) {
    cb.checked = !cb.checked;
    updateVoteButton();
  }
}

function updateVoteButton() {
  var checked = document.querySelectorAll('.candidate-checkbox:checked').length;
  var btn = document.getElementById('voteMultipleBtn');
  if (btn) {
    btn.disabled = checked === 0;
  }
}
</script>
@endpush

@endsection
