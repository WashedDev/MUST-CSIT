@php
  $startOfMonth = $date->copy()->startOfMonth();
  $endOfMonth = $date->copy()->endOfMonth();
  $startDayOfWeek = $startOfMonth->dayOfWeek; // 0=Sun, 6=Sat
  $daysInMonth = $date->daysInMonth;
  $today = now()->format('Y-m-d');
  $prevMonth = $date->copy()->subMonth()->format('Y-m');
  $nextMonth = $date->copy()->addMonth()->format('Y-m');
  $dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
@endphp

<div class="calendar-nav">
  <a href="{{ route('events.index', ['view' => 'calendar', 'month' => $prevMonth]) }}" class="btn btn-ghost btn-sm">&larr; {{ $date->copy()->subMonth()->format('M Y') }}</a>
  <h3>{{ $date->format('F Y') }}</h3>
  <a href="{{ route('events.index', ['view' => 'calendar', 'month' => $nextMonth]) }}" class="btn btn-ghost btn-sm">{{ $date->copy()->addMonth()->format('M Y') }} &rarr;</a>
</div>

<div class="calendar-grid">
  @foreach($dayNames as $name)
    <div class="calendar-header-cell">{{ $name }}</div>
  @endforeach

  {{-- Empty cells before the 1st --}}
  @for($i = 0; $i < $startDayOfWeek; $i++)
    <div class="calendar-cell calendar-cell--empty"></div>
  @endfor

  {{-- Day cells --}}
  @for($day = 1; $day <= $daysInMonth; $day++)
    @php
      $dateStr = $date->copy()->day($day)->format('Y-m-d');
      $dayEvents = $eventsByDay[$dateStr] ?? collect();
      $isToday = $dateStr === $today;
    @endphp
    <div class="calendar-cell {{ $isToday ? 'calendar-cell--today' : '' }} {{ $dayEvents->count() ? 'calendar-cell--has-events' : '' }}">
      <div class="calendar-day-number">{{ $day }}</div>
      @foreach($dayEvents as $e)
        <div class="calendar-event" data-event-id="{{ $e->id }}" role="button" tabindex="0" title="{{ $e->title }}">
          <span class="calendar-event-dot"></span>
          <span class="calendar-event-title">{{ Str::limit($e->title, 20) }}</span>
          @if(!empty($e->user_booked))
            <span class="calendar-event-booked" title="You are booked">&#10003;</span>
          @endif
        </div>
      @endforeach
    </div>
  @endfor
</div>

@push('scripts')
<script>
(function() {
  document.querySelectorAll('.calendar-event').forEach(function(el) {
    el.addEventListener('click', function() {
      var id = el.getAttribute('data-event-id');
      if (window.openEventModal) {
        window.openEventModal(id);
      }
    });
    el.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        var id = el.getAttribute('data-event-id');
        if (window.openEventModal) window.openEventModal(id);
      }
    });
  });
})();
</script>
@endpush
