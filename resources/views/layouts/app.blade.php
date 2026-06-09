<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','MUST CSIT Society')</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<a href="#main-content" class="skip-link">Skip to main content</a>

<div class="topstrip" role="region" aria-label="University information">
  <div class="container">
    <span>Malawi University of Science and Technology — CSIT Society</span>
    <span>
      <a href="https://www.must.ac.mw/" target="_blank" rel="noopener noreferrer">MUST Main Site</a>
      <a href="mailto:csit@must.ac.mw">csit@must.ac.mw</a>
    </span>
  </div>
</div>

<header class="site-header" role="banner">
  <div class="container">
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

    <nav class="primary" id="primaryNav" aria-label="Main navigation">
      <a href="{{ route('landing') }}" class="{{ request()->routeIs('landing') ? 'active' : '' }}" aria-current="{{ request()->routeIs('landing') ? 'page' : 'false' }}">Home</a>
      @auth
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">Dashboard</a>
        <a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'active' : '' }}" aria-current="{{ request()->routeIs('articles.*') ? 'page' : 'false' }}">News</a>
        <a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') || request()->routeIs('elections.*') ? 'active' : '' }}" aria-current="{{ request()->routeIs('events.*') ? 'page' : 'false' }}">Events</a>
        <a href="{{ route('documents.index') }}" class="{{ request()->routeIs('documents.*') ? 'active' : '' }}" aria-current="{{ request()->routeIs('documents.*') ? 'page' : 'false' }}">Repository</a>
        @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.members.index') }}" class="{{ request()->routeIs('admin.members.*') ? 'active' : '' }}" aria-current="{{ request()->routeIs('admin.members.*') ? 'page' : 'false' }}">Members</a>
        @endif
        <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}" aria-current="{{ request()->routeIs('profile') ? 'page' : 'false' }}">Profile</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;margin:0">@csrf
          <button class="btn btn-ghost btn-sm" type="submit">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}" class="btn btn-accent btn-sm">Join Society</a>
      @endauth
    </nav>

    <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="Toggle navigation menu" aria-expanded="false">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>
  </div>
</header>

<main id="main-content" role="main">
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
        <a href="{{ route('login') }}">Member Login</a>
        <a href="{{ route('register') }}">Join Society</a>
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

<script>
(function() {
  var nav = document.getElementById('primaryNav');
  var toggle = document.getElementById('mobileNavToggle');

  toggle.addEventListener('click', function() {
    nav.classList.toggle('open');
    var isOpen = nav.classList.contains('open');
    toggle.setAttribute('aria-expanded', isOpen);
  });
})();
</script>
</body>
</html>
