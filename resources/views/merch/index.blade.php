@extends('layouts.dashboard')
@section('title', 'Merch Store — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Merch Store</h1>
    <p>Rep the CSIT Society in style.</p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif

@if(session('info'))
  <div class="alert alert-success" role="alert">{{ session('info') }}</div>
@endif

@if($items->count())
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;margin-top:24px">
    @foreach($items as $item)
      <a href="{{ route('merch.show', $item) }}" class="merch-card" style="text-decoration:none;color:inherit">
        <div class="merch-card-img">
          @if($item->image)
            <img src="{{ $item->imageUrl() }}" alt="{{ $item->name }}">
          @else
            <div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--ink-300);font-size:2rem">CS</div>
          @endif
        </div>
        <div class="merch-card-body">
          <h4>{{ $item->name }}</h4>
          <div class="merch-card-price">MWK {{ number_format((float) $item->price) }}</div>
          <div style="font-size:0.8rem;color:var(--ink-400);margin-top:4px">
            @if($item->stock > 0)
              {{ $item->stock }} left
            @else
              <span style="color:#DC2626">Sold out</span>
            @endif
          </div>
        </div>
      </a>
    @endforeach
  </div>

  <div style="margin-top:24px">{{ $items->links() }}</div>
@else
  <div class="dash-card" style="text-align:center;padding:48px;margin-top:24px">
    <p style="color:var(--ink-400)">No merch available yet. Check back soon!</p>
  </div>
@endif

@endsection
