@extends('layouts.app')
@section('title','Events — MUST CSIT Society')
@section('content')

<div class="page-head">
  <div class="container">
    <h1>Society Events</h1>
    <p>Workshops, hackathons, talks and outreach programmes for members.</p>
  </div>
</div>

<section class="block">
  <div class="container">
    @foreach($events as $e)
      @php [$mon,$dayPart] = explode(' ', $e['date']); $day = rtrim(strstr($dayPart, ','), ','); @endphp
      <div class="event">
        <div class="date"><div class="d">{{ $day }}</div><div class="m">{{ $mon }}</div></div>
        <div>
          <strong>{{ $e['title'] }}</strong>
          <div style="color:var(--muted);font-size:.9rem">{{ $e['location'] }} · {{ $e['date'] }}</div>
        </div>
        <span class="tag">{{ $e['tag'] }}</span>
      </div>
    @endforeach
  </div>
</section>

@endsection
