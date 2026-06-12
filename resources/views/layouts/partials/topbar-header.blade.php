<div class="topstrip" role="region" aria-label="University information">
  <div class="container">
    <span>Malawi University of Science and Technology — CSIT Society</span>
    <span>
      <a href="https://www.must.ac.mw/" target="_blank" rel="noopener noreferrer">MUST Main Site</a>
      <a href="mailto:csit@must.ac.mw">csit@must.ac.mw</a>
    </span>
  </div>
</div>

<header class="site-header site-header--topnav" role="banner">
  <div class="container">
    <div class="topnav-row">
      <a href="{{ route('landing') }}" class="brand" aria-label="CSIT Society Home">
        <div class="brand-logo" aria-hidden="true">
          @if(file_exists(public_path('images/csit-logo.jpg')))
            <img src="{{ asset('images/csit-logo.jpg') }}" alt="">
          @else
            <span>CS</span>
          @endif
        </div>
        <div class="brand-text">
          <span class="brand-title">CSIT Society</span>
          <span class="brand-subtitle">MUST · Ndata</span>
        </div>
      </a>

      <div class="topnav-actions">
        <div style="position:relative">
          <button class="topbar-btn notif-btn" aria-label="Notifications" data-notif-target="topbarNotifDropdown">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            <span class="notif-dot notif-dot--count" aria-label="Unread notifications" style="display:none"></span>
          </button>
          <div class="notif-dropdown" id="topbarNotifDropdown"></div>
        </div>

        <a href="{{ route('merch.cart') }}" class="topbar-btn" title="Shopping cart" aria-label="Shopping cart" style="position:relative;text-decoration:none">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:20px;height:20px"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
          @php $cartCount = auth()->user()->cartCount(); @endphp
          @if($cartCount > 0)
            <span style="position:absolute;top:-4px;right:-4px;background:var(--primary);color:#fff;font-size:0.6rem;font-weight:700;width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;line-height:1">{{ $cartCount }}</span>
          @endif
        </a>

        <a href="{{ route('profile') }}" class="topbar-btn" title="Profile" aria-label="Profile" style="text-decoration:none">
          <div class="user-avatar-sm" aria-hidden="true">{{ strtoupper(substr(auth()->user()->firstname,0,1)) }}{{ strtoupper(substr(auth()->user()->lastname,0,1)) }}</div>
        </a>

        <button class="topbar-btn theme-toggle" type="button" title="Toggle dark mode" aria-label="Toggle dark mode" style="cursor:pointer;border:none;background:none;color:inherit">
          <svg class="theme-icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
          <svg class="theme-icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;display:none"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>

        <form method="POST" action="{{ route('logout') }}" style="display:inline;margin:0">@csrf
          <button class="topbar-btn" type="submit" title="Logout" aria-label="Logout" style="cursor:pointer;border:none;background:none;color:inherit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          </button>
        </form>

        <div class="topnav-divider" aria-hidden="true"></div>

        <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="Toggle navigation menu" aria-expanded="false">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
      </div>
    </div>

    <nav class="primary" id="primaryNav" aria-label="Main navigation">
      <a href="{{ route('landing') }}" class="{{ request()->routeIs('landing') ? 'active' : '' }}">Home</a>
      <a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'active' : '' }}">News</a>
      <a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') || request()->routeIs('elections.*') ? 'active' : '' }}">Events</a>
      <a href="{{ route('merch.index') }}" class="{{ request()->routeIs('merch.index') || request()->routeIs('merch.show') ? 'active' : '' }}">Merch Store</a>
      <a href="{{ route('documents.index') }}" class="{{ request()->routeIs('documents.*') ? 'active' : '' }}">Repository</a>
      @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">Events</a>
        <a href="{{ route('admin.merch.index') }}" class="{{ request()->routeIs('admin.merch.*') ? 'active' : '' }}">Merch</a>
        <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">Payments</a>
        <a href="{{ route('admin.members.index') }}" class="{{ request()->routeIs('admin.members.*') ? 'active' : '' }}">Members</a>
      @endif
    </nav>
  </div>
</header>

@once
  @push('scripts')
    <script>
    (function() {
      var nav = document.getElementById('primaryNav');
      var toggle = document.getElementById('mobileNavToggle');
      if (nav && toggle) {
        toggle.addEventListener('click', function() {
          nav.classList.toggle('open');
          var isOpen = nav.classList.contains('open');
          toggle.setAttribute('aria-expanded', isOpen);
        });
      }
    })();
    </script>
  @endpush
@endonce
