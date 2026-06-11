@php $allImages = $merchItem->allImages(); @endphp

<div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
  <div style="background:var(--surface-alt);display:flex;flex-direction:column;border-radius:var(--radius-lg) 0 0 var(--radius-lg);overflow:hidden">
    <div style="flex:1;display:flex;align-items:center;justify-content:center;min-height:260px;overflow:hidden">
      @if(count($allImages) > 0)
        <img id="modal-main-img" src="{{ $allImages[0] }}" alt="{{ $merchItem->name }}" style="width:100%;height:100%;object-fit:cover;display:block;transition:opacity 0.2s">
      @else
        <span style="font-size:3rem;font-weight:800;color:var(--ink-300)">CS</span>
      @endif
    </div>
    @if(count($allImages) > 1)
      <div style="display:flex;gap:6px;padding:10px 12px;border-top:1px solid var(--border);overflow-x:auto;flex-shrink:0">
        @foreach($allImages as $i => $src)
          <img class="merch-thumb {{ $i === 0 ? 'active' : '' }}" data-src="{{ $src }}" src="{{ $src }}" alt="" style="width:52px;height:52px;object-fit:cover;border-radius:var(--radius-sm);cursor:pointer;border:2px solid transparent;flex-shrink:0;opacity:0.6;transition:all 0.15s"
               onmouseover="this.style.opacity='1'" onmouseout="if(!this.classList.contains('active'))this.style.opacity='0.6'"
               onclick="document.querySelectorAll('.merch-thumb').forEach(function(x){x.classList.remove('active');x.style.borderColor='transparent';x.style.opacity='0.6'});this.classList.add('active');this.style.borderColor='var(--primary)';this.style.opacity='1';document.getElementById('modal-main-img').src=this.getAttribute('data-src')">
        @endforeach
      </div>
    @endif
  </div>
  <div style="padding:28px;display:flex;flex-direction:column">
    <h2 style="font-size:1.25rem;margin:0 0 4px">{{ $merchItem->name }}</h2>
    <div style="font-size:1.2rem;font-weight:700;color:var(--primary)">MWK {{ number_format((float) $merchItem->price) }}</div>

    @if($merchItem->description)
      <p style="margin-top:12px;color:var(--ink-secondary);line-height:1.6;font-size:0.9rem;flex:1">{{ $merchItem->description }}</p>
    @endif

    <div style="margin-top:12px;padding:10px 14px;background:var(--surface-alt);border-radius:var(--radius-sm);font-size:0.85rem">
      @if($merchItem->stock > 0)
        <span style="color:var(--ink-500)">Stock: <strong>{{ $merchItem->stock }}</strong> available</span>
      @else
        <span style="color:#DC2626;font-weight:600">Sold out</span>
      @endif
    </div>

    @if($merchItem->inStock())
      <form class="add-cart-form-modal" method="POST" action="{{ route('merch.cart.add-json', $merchItem) }}" style="margin-top:16px">
        @csrf
        <div style="display:flex;gap:8px;align-items:center;margin-bottom:10px">
          <label for="modal-qty" style="font-size:0.85rem;font-weight:600">Qty:</label>
          <input type="number" id="modal-qty" name="quantity" value="1" min="1" max="{{ $merchItem->stock }}" required style="width:70px;padding:8px 10px;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-size:0.9rem">
        </div>
        <button type="submit" class="btn btn-primary btn-block" id="modal-add-btn" data-price="{{ $merchItem->price }}">
          Add to Cart &middot; MWK {{ number_format((float) $merchItem->price) }}
        </button>
      </form>
    @endif
  </div>
</div>

<style>
.merch-thumb.active { border-color: var(--primary) !important; opacity: 1 !important; }
</style>
