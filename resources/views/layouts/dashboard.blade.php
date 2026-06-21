<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Dashboard — MUST CSIT Society')</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body @php $bodyClasses = []; if(request()->routeIs('landing')) $bodyClasses[] = 'route-landing'; if(auth()->user()->isAdmin() || session('layout') !== 'topbar') $bodyClasses[] = 'dash-body'; @endphp class="{{ implode(' ', $bodyClasses) }}">

@if(!auth()->user()->isAdmin() && session('layout') === 'topbar')
  {{-- === Topbar mode (landing page style) === --}}
  @include('layouts.partials.topbar-header')

  <main class="dash-content" id="main-content" role="main">
    @yield('content')
  </main>

  <footer role="contentinfo">
    <div class="container">
      <div class="grid-4">
        <div>
          <h4>CSIT Society</h4>
          <p>The Computer Science & IT Society at the Malawi University of Science and Technology.</p>
        </div>
        <div>
          <h4>Explore</h4>
          <a href="{{ route('landing') }}">Home</a>
          <a href="{{ route('dashboard') }}">Dashboard</a>
          <a href="{{ route('profile') }}">Profile</a>
        </div>
        <div>
          <h4>University</h4>
          <a href="https://www.must.ac.mw/" target="_blank" rel="noopener noreferrer">MUST Home</a>
          <a href="https://www.must.ac.mw/" target="_blank" rel="noopener noreferrer">Faculty of Computing</a>
        </div>
        <div>
          <h4>Contact</h4>
          <a href="mailto:csit@must.ac.mw">csit@must.ac.mw</a>
          <span class="text-muted mt-1">P.O. Box 5196, Limbe</span>
        </div>
      </div>
      <div class="copy">&copy; {{ date('Y') }} MUST CSIT Society. All rights reserved.</div>
    </div>
  </footer>
@else
  {{-- === Sidebar mode (current dashboard) === --}}
  <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

  <div class="dash-wrapper">
    <aside class="dash-sidebar" id="sidebar" role="navigation" aria-label="Sidebar navigation">
      <div class="sidebar-brand">
        <div class="sidebar-brand-logo">
          @if(file_exists(public_path('images/csit-logo.jpg')))
            <img src="{{ asset('images/csit-logo.jpg') }}" alt="">
          @else
            <span>CS</span>
          @endif
        </div>
        <div class="sidebar-text">
          <div class="s1">CSIT Society</div>
          <div class="s2">MUST · Ndata</div>
        </div>
      </div>

      <nav class="sidebar-nav">
        <div class="sidebar-section-title">Main</div>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
          </span>
          <span class="nav-label">Dashboard</span>
        </a>
        <a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'active' : '' }}">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><line x1="8" y1="7" x2="16" y2="7"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
          </span>
          <span class="nav-label">News</span>
        </a>
        <a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') || request()->routeIs('elections.*') ? 'active' : '' }}">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          </span>
          <span class="nav-label">Events</span>
        </a>
        <a href="{{ route('polls.index') }}" class="{{ request()->routeIs('polls.*') ? 'active' : '' }}">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          </span>
          <span class="nav-label">Polls</span>
        </a>
        <a href="{{ route('merch.index') }}" class="{{ request()->routeIs('merch.index') || request()->routeIs('merch.show') ? 'active' : '' }}">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
          </span>
          <span class="nav-label">Merch Store</span>
        </a>
        <a href="{{ route('merch.cart') }}" class="{{ request()->routeIs('merch.cart') ? 'active' : '' }}" style="position:relative">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
          </span>
          <span class="nav-label">Cart</span>
          @php $cartCount = auth()->user()->cartCount(); @endphp
          @if($cartCount > 0)
            <span class="cart-badge" style="position:absolute;right:16px;top:50%;transform:translateY(-50%);background:var(--primary);color:#fff;font-size:0.7rem;font-weight:700;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center">{{ $cartCount }}</span>
          @endif
        </a>
        <a href="{{ route('documents.index') }}" class="{{ request()->routeIs('documents.*') ? 'active' : '' }}">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          </span>
          <span class="nav-label">Repository</span>
        </a>
        @if(auth()->user()->isAdmin())
          <div class="sidebar-section-title">Administration</div>
          <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
            <span class="nav-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </span>
            <span class="nav-label">Events</span>
          </a>
          <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <span class="nav-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            </span>
            <span class="nav-label">Payments</span>
          </a>
          <a href="{{ route('admin.merch.index') }}" class="{{ request()->routeIs('admin.merch.*') ? 'active' : '' }}">
            <span class="nav-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
            </span>
            <span class="nav-label">Merch</span>
          </a>
          <a href="{{ route('admin.members.index') }}" class="{{ request()->routeIs('admin.members.*') && !request()->routeIs('admin.members.pending') ? 'active' : '' }}">
            <span class="nav-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </span>
            <span class="nav-label">Members</span>
          </a>
          <a href="{{ route('admin.members.pending') }}" class="{{ request()->routeIs('admin.members.pending') ? 'active' : '' }}">
            <span class="nav-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </span>
            <span class="nav-label">Pending</span>
          </a>
          <a href="{{ route('admin.audit-logs.index') }}" class="{{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
            <span class="nav-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </span>
            <span class="nav-label">Audit Logs</span>
          </a>
          <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <span class="nav-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            </span>
            <span class="nav-label">Settings</span>
          </a>
          <a href="{{ route('admin.articles.pending') }}" class="{{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
            <span class="nav-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            </span>
            <span class="nav-label">Articles</span>
          </a>
        @endif
        <div class="sidebar-section-title">Account</div>
        <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </span>
          <span class="nav-label">Profile</span>
        </a>
      </nav>

      <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
          @csrf
          <button class="sidebar-logout" type="submit" aria-label="Logout">
            <span class="nav-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </span>
            <span class="nav-label">Logout</span>
          </button>
        </form>
      </div>
    </aside>

    <div class="dash-main">
      <header class="dash-topbar" role="banner">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>

        <div class="search-wrapper">
          <div class="topbar-search" role="search">
            <span class="search-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </span>
            <input type="search" id="global-search" placeholder="Search events, articles, merch, documents&hellip;" aria-label="Search" autocomplete="off">
          </div>
          <div class="search-dropdown" id="search-dropdown"></div>
        </div>

        <div class="topbar-right">
          @unless(auth()->user()->isAdmin())
          <form method="POST" action="{{ route('layout.toggle') }}" style="margin:0;display:flex">
            @csrf
            <button class="topbar-btn" type="submit" title="Switch to topbar navigation" aria-label="Switch layout">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
          </form>
          @endunless

          <button class="topbar-btn theme-toggle" type="button" title="Toggle dark mode" aria-label="Toggle dark mode" style="cursor:pointer;border:none;background:none;color:inherit">
            <svg class="theme-icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
            <svg class="theme-icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;display:none"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
          </button>

          <a href="{{ route('landing') }}" class="topbar-btn" title="Visit public site" aria-label="Public site">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
          </a>

          <a href="{{ route('merch.cart') }}" class="topbar-btn" title="Shopping cart" aria-label="Shopping cart" style="position:relative;text-decoration:none">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:20px;height:20px"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            @php $cartCount = auth()->user()->cartCount(); @endphp
            @if($cartCount > 0)
              <span style="position:absolute;top:-4px;right:-4px;background:var(--primary);color:#fff;font-size:0.6rem;font-weight:700;width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;line-height:1"> {{ $cartCount }} </span>
            @endif
          </a>

          <div style="position:relative">
            <button class="topbar-btn notif-btn" aria-label="Notifications" data-notif-target="sidebarNotifDropdown">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              <span class="notif-dot notif-dot--count" aria-label="Unread notifications" style="display:none"></span>
            </button>
            <div class="notif-dropdown" id="sidebarNotifDropdown"></div>
          </div>

          <div class="topbar-divider" aria-hidden="true"></div>

          <div class="topbar-user" tabindex="0" role="button" aria-label="User menu">
            <div class="user-avatar" aria-hidden="true">{{ strtoupper(substr(auth()->user()->firstname,0,1)) }}{{ strtoupper(substr(auth()->user()->lastname,0,1)) }}</div>
            <div class="user-info">
              <div class="user-name">{{ auth()->user()->name }}</div>
              <div class="user-role">{{ auth()->user()->isAdmin() ? 'Admin' : 'Member' }}</div>
            </div>
          </div>
        </div>
      </header>

      <main class="dash-content" id="main-content" role="main">
        @yield('content')
      </main>
    </div>
  </div>
@endif

@stack('scripts')

@if(session('layout') !== 'topbar')
<script>
(function() {
  var sidebar = document.getElementById('sidebar');
  var toggle = document.getElementById('sidebarToggle');
  var overlay = document.getElementById('sidebarOverlay');

  function isMobile() { return window.innerWidth <= 860; }

  function toggleSidebar() {
    if (isMobile()) {
      sidebar.classList.toggle('open');
      overlay.classList.toggle('active');
      var isOpen = sidebar.classList.contains('open');
      toggle.setAttribute('aria-expanded', isOpen);
      document.body.style.overflow = isOpen ? 'hidden' : '';
    } else {
      sidebar.classList.toggle('collapsed');
      var isCollapsed = sidebar.classList.contains('collapsed');
      toggle.setAttribute('aria-expanded', !isCollapsed);
    }
  }

  if (toggle) toggle.addEventListener('click', toggleSidebar);

  if (overlay) overlay.addEventListener('click', function() {
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
    toggle.setAttribute('aria-expanded', false);
    document.body.style.overflow = '';
  });

  window.addEventListener('resize', function() {
    if (!isMobile()) {
      if (sidebar) sidebar.classList.remove('open');
      if (overlay) overlay.classList.remove('active');
      document.body.style.overflow = '';
    }
  });

  var activeLink = sidebar && sidebar.querySelector('.sidebar-nav a.active');
  if (activeLink) {
    activeLink.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
  }
})();
</script>
@endif

<script>
(function() {
  var dots = document.querySelectorAll('.notif-dot--count');
  var openDropdown = null;

  function updateDot(count) {
    dots.forEach(function(d) {
      if (count > 0) {
        d.textContent = count > 99 ? '99+' : count;
        d.style.display = 'flex';
      } else {
        d.style.display = 'none';
      }
    });
  }

  function loadDropdown(targetId, btn) {
    var dd = document.getElementById(targetId);
    if (!dd) return;

    if (openDropdown && openDropdown !== dd) {
      openDropdown.classList.remove('open');
    }

    if (dd.classList.contains('open')) {
      dd.classList.remove('open');
      openDropdown = null;
      return;
    }

    dd.innerHTML = '<div style="padding:32px;text-align:center"><div class="spinner"></div></div>';
    dd.classList.add('open');
    openDropdown = dd;

    fetch('/notifications/dropdown')
      .then(function(r) { return r.json(); })
      .then(function(data) {
        dd.innerHTML = data.html;
        updateDot(data.unread_count);
      })
      .catch(function() {
        dd.innerHTML = '<div style="padding:32px;text-align:center;color:var(--accent);font-size:0.82rem">Failed to load.</div>';
      });
  }

  document.querySelectorAll('.notif-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      loadDropdown(btn.getAttribute('data-notif-target'), btn);
    });
  });

  document.addEventListener('click', function(e) {
    if (openDropdown && !e.target.closest('.notif-btn, .notif-dropdown, .notif-link, .notif-link-form')) {
      openDropdown.classList.remove('open');
      openDropdown = null;
    }
  });

  // Fetch initial unread count
  fetch('/notifications/unread-count')
    .then(function(r) { return r.json(); })
    .then(function(data) { updateDot(data.count); })
    .catch(function() {});

  // Dark mode toggle
  (function() {
    var html = document.documentElement;
    var saved = localStorage.getItem('theme');
    if (saved === 'dark') html.setAttribute('data-theme', 'dark');

    function updateIcons(isDark) {
      document.querySelectorAll('.theme-toggle').forEach(function(btn) {
        var sun = btn.querySelector('.theme-icon-sun');
        var moon = btn.querySelector('.theme-icon-moon');
        if (sun) sun.style.display = isDark ? 'none' : '';
        if (moon) moon.style.display = isDark ? '' : 'none';
      });
    }
    updateIcons(saved === 'dark');

    document.querySelectorAll('.theme-toggle').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var isDark = html.getAttribute('data-theme') === 'dark';
        if (isDark) {
          html.removeAttribute('data-theme');
          localStorage.setItem('theme', 'light');
        } else {
          html.setAttribute('data-theme', 'dark');
          localStorage.setItem('theme', 'dark');
        }
        updateIcons(!isDark);
      });
    });
  })();

  // Client-side form validation
  document.querySelectorAll('form').forEach(initFormValidation);

  // Global search
  var searchWrapper = document.querySelector('.search-wrapper');
  var searchInput = document.getElementById('global-search');
  var searchDropdown = document.getElementById('search-dropdown');
  var searchTimer;

  if (searchInput && searchDropdown) {
    searchInput.addEventListener('input', function() {
      clearTimeout(searchTimer);
      var val = this.value.trim();
      if (val.length < 2) {
        searchDropdown.classList.remove('open');
        return;
      }
      searchTimer = setTimeout(function() {
        fetch('/search?q=' + encodeURIComponent(val), {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
          if (!data.results || data.results.length === 0) {
            searchDropdown.innerHTML = '<div class="sd-empty">No results found.</div>';
            searchDropdown.classList.add('open');
            return;
          }
          var groups = {};
          data.results.forEach(function(item) {
            if (!groups[item.type]) groups[item.type] = [];
            groups[item.type].push(item);
          });
          var html = '';
          var typeLabels = { event:'Events', article:'Articles', merch:'Merch', document:'Documents', member:'Members' };
          Object.keys(groups).forEach(function(type) {
            html += '<div class="sd-group-title">' + (typeLabels[type] || type) + '</div>';
            groups[type].forEach(function(item) {
              html += '<a href="' + item.url + '" class="sd-item">' +
                '<span><div class="sd-title">' + escapeHtml(item.title) + '</div>' +
                '<div class="sd-meta">' + escapeHtml(item.meta || '') + '</div></span>' +
                '<span class="sd-type">' + type + '</span></a>';
            });
          });
          searchDropdown.innerHTML = html;
          searchDropdown.classList.add('open');
        })
        .catch(function() {
          searchDropdown.classList.remove('open');
        });
      }, 300);
    });

    document.addEventListener('click', function(e) {
      if (!searchWrapper.contains(e.target)) {
        searchDropdown.classList.remove('open');
      }
    });

    searchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        searchDropdown.classList.remove('open');
        this.blur();
      }
    });
  }

  function escapeHtml(str) {
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
  }

  // Toast notifications
  window.showToast = function(message, type) {
    type = type || 'success';
    var container = document.getElementById('toast-container');
    if (!container) return;
    var toast = document.createElement('div');
    toast.className = 'toast toast--' + type;
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(function() { toast.classList.add('toast--visible'); }, 10);
    setTimeout(function() {
      toast.classList.remove('toast--visible');
      setTimeout(function() { toast.remove(); }, 300);
    }, 3500);
  };

  // Read flash messages from hidden element
  var flash = document.getElementById('flash-toast');
  if (flash) {
    var msg = flash.getAttribute('data-message');
    var type = flash.getAttribute('data-type') || 'success';
    if (msg) showToast(msg, type);
  }
})();
</script>

<div id="toast-container" aria-live="polite" aria-atomic="true"></div>
@php
  $_flashMsg = session('success') ?: session('info') ?: session('error') ?: ($errors->first() ?? '');
  $_flashType = session('success') ? 'success' : (session('info') ? 'info' : (session('error') || $errors->first() ? 'error' : ''));
@endphp
<div id="flash-toast" data-message="{{ $_flashMsg }}" data-type="{{ $_flashType }}" style="display:none"></div>
<script src="{{ asset('js/form-validation.js') }}"></script>
</body>
</html>
