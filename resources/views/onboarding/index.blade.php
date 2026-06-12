@extends('layouts.dashboard')
@section('title', 'Welcome — MUST CSIT Society')
@section('content')

<div style="max-width:640px;margin:48px auto;text-align:center">
  <h1 style="font-size:1.75rem;margin-bottom:8px">Welcome to CSIT Society!</h1>
  <p style="color:var(--ink-500);margin-bottom:32px">Let&rsquo;s get you started in 3 quick steps.</p>

  <div id="onboarding-steps" style="text-align:left">
    <div class="onboarding-step" data-step="1">
      <div class="step-header" style="display:flex;align-items:center;gap:12px;padding:16px;background:var(--surface);border-radius:var(--radius-md);margin-bottom:12px;box-shadow:var(--shadow-sm)">
        <div class="step-number" style="width:36px;height:36px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;flex-shrink:0">1</div>
        <div style="flex:1">
          <strong style="font-size:1rem">Complete Your Profile</strong>
          <p style="margin:4px 0 0;font-size:0.85rem;color:var(--ink-500)">Your registration details are saved. Add a profile picture or update your info on your profile page.</p>
        </div>
        <a href="{{ route('profile') }}" class="btn btn-primary btn-sm" target="_blank">Go to Profile</a>
      </div>
    </div>

    <div class="onboarding-step" data-step="2">
      <div class="step-header" style="display:flex;align-items:center;gap:12px;padding:16px;background:var(--surface);border-radius:var(--radius-md);margin-bottom:12px;box-shadow:var(--shadow-sm)">
        <div class="step-number" style="width:36px;height:36px;border-radius:50%;background:var(--ink-300);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;flex-shrink:0">2</div>
        <div style="flex:1">
          <strong style="font-size:1rem">Browse Events</strong>
          <p style="margin:4px 0 0;font-size:0.85rem;color:var(--ink-500)">Check out upcoming society events and book your spot.</p>
        </div>
        <a href="{{ route('events.index') }}" class="btn btn-outline btn-sm" target="_blank">View Events</a>
      </div>
    </div>

    <div class="onboarding-step" data-step="3">
      <div class="step-header" style="display:flex;align-items:center;gap:12px;padding:16px;background:var(--surface);border-radius:var(--radius-md);margin-bottom:12px;box-shadow:var(--shadow-sm)">
        <div class="step-number" style="width:36px;height:36px;border-radius:50%;background:var(--ink-300);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;flex-shrink:0">3</div>
        <div style="flex:1">
          <strong style="font-size:1rem">Visit the Merch Store</strong>
          <p style="margin:4px 0 0;font-size:0.85rem;color:var(--ink-500)">Grab some CSIT Society branded merchandise.</p>
        </div>
        <a href="{{ route('merch.index') }}" class="btn btn-outline btn-sm" target="_blank">Visit Store</a>
      </div>
    </div>
  </div>

  <div style="margin-top:32px">
    <form method="POST" action="{{ route('onboarding.complete') }}">
      @csrf
      <button type="submit" class="btn btn-primary" id="finish-onboarding" style="padding:14px 48px;font-size:1.05rem">
        Done &mdash; Take Me to Dashboard
      </button>
    </form>
  </div>
</div>

@push('scripts')
<script>
  (function() {
    var steps = document.querySelectorAll('.onboarding-step');
    var checkInterval = setInterval(function() {
      steps.forEach(function(step) {
        var link = step.querySelector('a');
        if (link && link.getAttribute('data-visited') !== 'true') {
          var href = link.getAttribute('href');
          if (href && !href.startsWith('http')) {
            // Can't detect external navigation, so we mark visited after they click
          }
        }
      });
    }, 1000);
  })();

  document.querySelectorAll('.onboarding-step a').forEach(function(link) {
    link.addEventListener('click', function() {
      var step = this.closest('.onboarding-step');
      var num = step.querySelector('.step-number');
      num.style.background = 'var(--primary)';
      this.setAttribute('data-visited', 'true');
      this.classList.remove('btn-outline');
      this.classList.add('btn-primary');
    });
  });
</script>
@endpush

@endsection
