@extends('layouts.dashboard')
@section('title', $merchItem->name . ' — CSIT Merch')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>{{ $merchItem->name }}</h1>
  </div>
</div>

@if($errors->any())
  <div class="alert alert-error" role="alert">{{ $errors->first() }}</div>
@endif

<div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;margin-top:24px;max-width:800px">
  <div class="merch-card-img" style="border-radius:var(--radius-md);overflow:hidden;background:var(--surface-alt)">
    @if($merchItem->image)
      <img src="{{ $merchItem->imageUrl() }}" alt="{{ $merchItem->name }}" style="width:100%;height:auto;display:block">
    @else
      <div style="display:flex;align-items:center;justify-content:center;height:300px;color:var(--ink-300);font-size:3rem">CS</div>
    @endif
  </div>

  <div>
    <h2 style="margin:0;font-size:1.5rem">{{ $merchItem->name }}</h2>
    <div style="font-size:1.25rem;font-weight:700;color:var(--primary);margin-top:8px">MWK {{ number_format((float) $merchItem->price) }}</div>

    @if($merchItem->description)
      <p style="margin-top:16px;color:var(--ink-500);line-height:1.6">{{ $merchItem->description }}</p>
    @endif

    <div style="margin-top:16px;padding:12px;background:var(--surface-alt);border-radius:var(--radius-sm);font-size:0.9rem">
      @if($merchItem->stock > 0)
        <span style="color:var(--ink-500)">Stock: <strong>{{ $merchItem->stock }}</strong> available</span>
      @else
        <span style="color:#DC2626">Sold out</span>
      @endif
    </div>

    @if($merchItem->inStock())
      <form method="POST" action="{{ route('merch.cart.add', $merchItem) }}" style="margin-top:24px">
        @csrf
        <div class="form-row" style="margin-bottom:12px">
          <label for="quantity">Quantity</label>
          <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $merchItem->stock }}" required style="max-width:100px">
        </div>
        <button type="submit" class="btn btn-primary btn-block" style="padding:14px">
          Add to Cart &middot; MWK {{ number_format((float) $merchItem->price) }}
        </button>
      </form>
    @endif

    <p style="margin-top:24px"><a href="{{ route('merch.index') }}">&larr; Back to store</a></p>
  </div>
</div>

<style>
  @media (max-width:640px) {
    div[style*="grid-template-columns: 1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }
</style>

<script>
document.getElementById('quantity')?.addEventListener('input', function() {
  var price = {{ $merchItem->price }};
  var total = (this.value * price).toLocaleString();
  var btn = this.closest('form').querySelector('button[type=submit]');
  btn.textContent = 'Add to Cart \u00b7 MWK ' + total;
});
</script>

@endsection
