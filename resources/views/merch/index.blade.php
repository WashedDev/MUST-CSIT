@extends('layouts.dashboard')
@section('title', 'Merch Store — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Merch Store</h1>
    <p>Rep the CSIT Society in style.</p>
  </div>
</div>

<div id="cartMessage" style="display:none" aria-hidden="true"></div>

@if($items->count())
  <div class="event-showcase-grid" style="margin-top:24px">
    @foreach($items as $item)
      <div class="event-showcase-card" style="display:flex;flex-direction:column;cursor:pointer" data-merch-id="{{ $item->id }}" role="button" tabindex="0">
        <div style="height:200px;background:var(--surface-alt);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
          @if($item->image)
            <img src="{{ $item->imageUrl() }}" alt="{{ $item->name }}" style="width:100%;height:100%;object-fit:cover;display:block">
          @else
            <span style="font-size:2.5rem;font-weight:800;color:var(--ink-300)">CS</span>
          @endif
        </div>
        <div class="event-showcase-body" style="flex:1;display:flex;flex-direction:column">
          <h3 style="font-size:1rem">{{ $item->name }}</h3>
          <div style="font-size:1.05rem;font-weight:700;color:var(--primary);margin:4px 0 8px">MWK {{ number_format((float) $item->price) }}</div>
          @if($item->description)
            <p style="font-size:0.82rem;color:var(--ink-secondary);line-height:1.5;margin:0 0 12px;flex:1;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">{{ $item->description }}</p>
          @endif
          <div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:auto">
            @if($item->inStock())
              <form class="add-cart-form" data-id="{{ $item->id }}" style="flex:1">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button class="btn btn-primary btn-sm btn-block" type="submit" style="font-size:0.78rem;padding:7px 12px">Add to Cart</button>
              </form>
            @else
              <button class="btn btn-sm btn-block" disabled style="font-size:0.78rem;padding:7px 12px;background:var(--border);color:var(--muted);border:none;cursor:not-allowed">Sold Out</button>
            @endif
            <button class="btn btn-outline btn-sm view-details-btn" data-merch-id="{{ $item->id }}" type="button" style="font-size:0.78rem;padding:7px 12px">Details</button>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div style="margin-top:24px">{{ $items->links() }}</div>
@else
  <div class="dash-card" style="text-align:center;padding:48px;margin-top:24px">
    <p style="color:var(--ink-400)">No merch available yet. Check back soon!</p>
  </div>
@endif

<div class="modal-overlay" id="merchModal" style="display:none" role="dialog" aria-modal="true" aria-label="Item details">
  <div class="modal-container" style="max-width:750px">
    <button class="modal-close" id="merchModalClose" aria-label="Close modal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <div class="modal-content" id="merchModalContent">
      <div class="modal-loader"><div class="spinner"></div></div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function() {
  var modal = document.getElementById('merchModal');
  var modalContent = document.getElementById('merchModalContent');
  var closeBtn = document.getElementById('merchModalClose');

  function showMessage(text, type) {
    if (window.showToast) {
      window.showToast(text, type || 'success');
    }
  }

  function updateCartBadge(count) {
    document.querySelectorAll('.cart-badge, [class*="cart-badge"]').forEach(function(el) {
      if (count > 0) {
        el.textContent = count;
        el.style.display = 'flex';
      } else {
        el.style.display = 'none';
      }
    });
    // also update topbar cart bubble
    document.querySelectorAll('.topbar-btn [style*="position:absolute"]').forEach(function(el) {
      if (count > 0) {
        el.textContent = ' ' + count + ' ';
        el.style.display = 'flex';
      } else {
        el.style.display = 'none';
      }
    });
  }

  function openModal(id) {
    modalContent.innerHTML = '<div class="modal-loader"><div class="spinner"></div></div>';
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    fetch('/merch/' + id + '/details')
      .then(function(r) { return r.json(); })
      .then(function(data) {
        modalContent.innerHTML = data.html;
        bindModalForms();
      })
      .catch(function() {
        modalContent.innerHTML = '<p style="text-align:center;padding:40px;color:var(--accent)">Failed to load item details.</p>';
      });
  }

  function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = '';
  }

  function bindModalForms() {
    modalContent.querySelectorAll('.add-cart-form-modal').forEach(function(f) {
      f.addEventListener('submit', function(e) {
        e.preventDefault();
        var form = e.target;
        var fd = new FormData(form);

        fetch(form.action, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
          .then(function(r) { return r.json(); })
          .then(function(data) {
            if (data.success) {
              showMessage(data.success, 'success');
              updateCartBadge(data.cart_count);
              closeModal();
            }
          })
          .catch(function() { showMessage('Failed to add item.', 'error'); });
      });
    });

    // modal qty updater
    var qty = document.getElementById('modal-qty');
    var btn = document.getElementById('modal-add-btn');
    if (qty && btn) {
      var price = parseFloat(btn.getAttribute('data-price'));
      qty.addEventListener('input', function() {
        var total = (this.value * price).toLocaleString();
        btn.innerHTML = 'Add to Cart \u00b7 MWK ' + total;
      });
    }

    // modal gallery thumb clicks
    modalContent.querySelectorAll('.merch-thumb').forEach(function(t) {
      t.addEventListener('click', function() {
        modalContent.querySelectorAll('.merch-thumb').forEach(function(x) { x.classList.remove('active'); });
        t.classList.add('active');
        var main = document.getElementById('modal-main-img');
        if (main) main.src = t.getAttribute('data-src');
      });
    });
  }

  // Card add-to-cart via AJAX
  document.querySelectorAll('.add-cart-form').forEach(function(f) {
    f.addEventListener('submit', function(e) {
      e.preventDefault();
      var form = e.target;
      var fd = new FormData(form);
      var id = form.getAttribute('data-id');

      fetch('/merch/cart/add-json/' + id, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(r) { return r.json(); })
        .then(function(data) {
          if (data.success) {
            showMessage(data.success, 'success');
            updateCartBadge(data.cart_count);
          }
        })
        .catch(function() { showMessage('Failed to add item.', 'error'); });
    });
  });

  // Card click opens modal
  document.querySelectorAll('[data-merch-id]').forEach(function(card) {
    card.addEventListener('click', function(e) {
      if (e.target.closest('form, .view-details-btn')) return;
      openModal(card.getAttribute('data-merch-id'));
    });
    card.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        openModal(card.getAttribute('data-merch-id'));
      }
    });
  });

  // View Details buttons on cards
  document.querySelectorAll('.view-details-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      openModal(btn.getAttribute('data-merch-id'));
    });
  });

  closeBtn.addEventListener('click', closeModal);

  modal.addEventListener('click', function(e) {
    if (e.target === modal) closeModal();
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.style.display !== 'none') closeModal();
  });
})();
</script>
@endpush
@endsection