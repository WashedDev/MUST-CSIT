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

<div class="topstrip">
  <div class="container">
    <span>Malawi University of Science and Technology — CSIT Society</span>
    <span>
      <a href="https://www.must.ac.mw/" target="_blank" rel="noopener">MUST Main Site</a>
      <a href="mailto:csit@must.ac.mw">csit@must.ac.mw</a>
    </span>
  </div>
</div>

<header class="site-header">
  <div class="container">
    <a href="{{ route('landing') }}" class="brand">
      <div class="logo" title="MUST logo">
        @if(file_exists(public_path('images/must-logo.jpg')))
          <img src="{{ asset('images/must-logo.jpg') }}" alt="MUST logo">
        @else MUST @endif
      </div>
      <div class="logo" title="CSIT logo" style="background:var(--must-red);color:var(--must-blue-dark)">
        @if(file_exists(public_path('images/csit-logo.jpg')))
          <img src="{{ asset('images/csit-logo.jpg') }}" alt="CSIT logo">
        @else CSIT @endif
      </div>
      <div class="titles">
        <span class="t1">CSIT Society</span>
        <span class="t2">MUST · Ndata</span>
      </div>
    </a>
    <nav class="primary">
      <a href="{{ route('landing') }}" class="{{ request()->routeIs('landing') ? 'active' : '' }}">Home</a>
      @auth
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('events') }}" class="{{ request()->routeIs('events') ? 'active' : '' }}">Events</a>
        <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">Profile</a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">@csrf
          <button class="btn btn-outline" type="submit">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}" class="btn btn-gold">Join Society</a>
      @endauth
    </nav>
  </div>
</header>

@yield('content')

<footer>
  <div class="container">
    <div class="grid-4">
      <div>
        <h4>CSIT Society</h4>
        <p style="color:#9ca3af;font-size:.9rem;margin:0">The Computer Science & IT Society at the Malawi University of Science and Technology.</p>
      </div>
      <div>
        <h4>Explore</h4>
        <a href="{{ route('landing') }}">Home</a>
        <a href="{{ route('login') }}">Member Login</a>
        <a href="{{ route('register') }}">Join Society</a>
      </div>
      <div>
        <h4>University</h4>
        <a href="https://www.must.ac.mw/" target="_blank" rel="noopener">MUST Home</a>
        <a href="https://www.must.ac.mw/" target="_blank" rel="noopener">Faculty of Computing</a>
      </div>
      <div>
        <h4>Contact</h4>
        <a href="mailto:csit@must.ac.mw">csit@must.ac.mw</a>
        <span style="color:#9ca3af;font-size:.9rem">P.O. Box 5196, Limbe</span>
      </div>
    </div>
    <div class="copy">© {{ date('Y') }} MUST CSIT Society. All rights reserved.</div>
  </div>
</footer>

</body>
</html>
