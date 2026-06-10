@extends('layouts.dashboard')
@section('title', 'Shopping Cart — CSIT Merch')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Shopping Cart</h1>
    <p class="dash-sub">{{ count($items) }} item{{ count($items) !== 1 ? 's' : '' }}</p>
  </div>
  <a href="{{ route('merch.index') }}" class="btn btn-outline">&larr; Continue Shopping</a>
</div>

@if(session('success'))
  <div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif

@if($errors->any())
  <div class="alert alert-error" role="alert">{{ $errors->first() }}</div>
@endif

@if(empty($items))
  <div class="empty-state" style="text-align:center;padding:64px 24px">
    <div style="font-size:3rem;margin-bottom:16px;opacity:0.3">&#128722;</div>
    <h2>Your cart is empty</h2>
    <p style="color:var(--ink-500)">Browse the store and add items to get started.</p>
    <a href="{{ route('merch.index') }}" class="btn btn-primary" style="margin-top:16px">Browse Store</a>
  </div>
@else
  <div style="margin-top:24px">
    @foreach($items as $entry)
      @php $item = $entry['item']; @endphp
      <div class="cart-item" style="display:flex;align-items:center;gap:16px;padding:16px;background:var(--surface);border-radius:var(--radius-md);margin-bottom:12px;box-shadow:var(--shadow-sm)">
        <div style="width:80px;height:80px;border-radius:var(--radius-sm);overflow:hidden;background:var(--surface-alt);flex-shrink:0">
          @if($item->image)
            <img src="{{ $item->imageUrl() }}" alt="{{ $item->name }}" style="width:100%;height:100%;object-fit:cover">
          @else
            <div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--ink-300);font-weight:700">CS</div>
          @endif
        </div>

        <div style="flex:1;min-width:0">
          <a href="{{ route('merch.show', $item) }}" style="font-weight:600;color:var(--ink-900);text-decoration:none">{{ $item->name }}</a>
          <div style="font-size:0.85rem;color:var(--ink-500);margin-top:2px">MWK {{ number_format((float) $item->price) }} each</div>
        </div>

        <form method="POST" action="{{ route('merch.cart.update', $item) }}" style="display:flex;align-items:center;gap:8px">
          @csrf
          <input type="number" name="quantity" value="{{ $entry['quantity'] }}" min="0" max="{{ $item->stock }}"
                 style="width:64px;padding:6px 8px;border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center"
                 onchange="this.form.submit()">
        </form>

        <div style="font-weight:700;color:var(--primary);white-space:nowrap;min-width:90px;text-align:right">
          MWK {{ number_format((float) $entry['subtotal']) }}
        </div>

        <form method="POST" action="{{ route('merch.cart.remove', $item) }}" style="margin:0">
          @csrf
          <button type="submit" class="btn btn-sm btn-ghost" style="color:var(--ink-400);padding:6px 10px" title="Remove">&times;</button>
        </form>
      </div>
    @endforeach

    <div style="display:flex;justify-content:space-between;align-items:center;padding:20px 16px;margin-top:16px;background:var(--surface);border-radius:var(--radius-md);box-shadow:var(--shadow-sm)">
      <div>
        <span style="color:var(--ink-500)">Total</span>
        <span style="font-size:1.5rem;font-weight:800;color:var(--primary);margin-left:12px">MWK {{ number_format((float) $total) }}</span>
      </div>

      <form method="POST" action="{{ route('merch.checkout') }}">
        @csrf
        <button type="submit" class="btn btn-primary" style="padding:14px 32px;font-size:1rem">
          Proceed to Checkout
        </button>
      </form>
    </div>
  </div>
@endif

<style>
  .cart-item:hover {
    box-shadow: var(--shadow-md) !important;
  }
  .btn-ghost {
    background: none;
    border: none;
    cursor: pointer;
    border-radius: var(--radius-sm);
    font-size: 1.2rem;
    line-height: 1;
  }
  .btn-ghost:hover {
    background: var(--surface-alt);
    color: #DC2626 !important;
  }
  @media (max-width:640px) {
    .cart-item {
      flex-wrap: wrap;
    }
    .cart-item > div:first-child {
      width: 60px;
      height: 60px;
    }
    .cart-item > div:nth-child(2) {
      flex: 1 1 calc(100% - 76px);
    }
    .cart-item form:nth-of-type(1) {
      order: 3;
      margin-left: 76px;
    }
    .cart-item > div:nth-child(4) {
      order: 4;
    }
    .cart-item form:nth-of-type(2) {
      order: 5;
    }
  }
</style>

@endsection
