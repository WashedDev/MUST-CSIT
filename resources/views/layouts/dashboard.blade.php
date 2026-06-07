<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Dashboard — MUST CSIT Society')</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="dash-body">

<div class="dash-wrapper">
  <aside class="dash-sidebar" id="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo">
        @if(file_exists(public_path('images/csit-logo.jpg')))
          <img src="{{ asset('images/csit-logo.jpg') }}" alt="CSIT">
        @else
          CSIT
        @endif
      </div>
      <div class="sidebar-title">
        <span class="st1">CSIT Society</span>
        <span class="st2">MUST · Ndata</span>
      </div>
    </div>

    <nav class="sidebar-nav">
      <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="si">◆</span> Dashboard
      </a>
      <a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'active' : '' }}">
        <span class="si">📰</span> News
      </a>
      <a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') || request()->routeIs('elections.*') ? 'active' : '' }}">
        <span class="si">📅</span> Events
      </a>
      <a href="{{ route('documents.index') }}" class="{{ request()->routeIs('documents.*') ? 'active' : '' }}">
        <span class="si">📁</span> Repository
      </a>
      @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.members.index') }}" class="{{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
          <span class="si">👥</span> Members
        </a>
      @endif
      <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
        <span class="si">👤</span> Profile
      </a>
    </nav>

    <div class="sidebar-footer">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="sidebar-logout" type="submit"><span class="si">🚪</span> Logout</button>
      </form>
    </div>
  </aside>

  <div class="dash-main">
    <header class="dash-topbar">
      <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">☰</button>
      <div class="topbar-search">
        <span class="search-icon">🔍</span>
        <input type="text" placeholder="Search..." disabled>
      </div>
      <div class="topbar-right">
        <a href="{{ route('landing') }}" class="topbar-link" title="Visit public site">🌐</a>
        <div class="topbar-user">
          <div class="user-avatar">{{ strtoupper(substr(auth()->user()->firstname,0,1)) }}{{ strtoupper(substr(auth()->user()->lastname,0,1)) }}</div>
          <div class="user-info">
            <span class="user-name">{{ auth()->user()->name }}</span>
            <span class="user-role">{{ auth()->user()->isAdmin() ? 'Admin' : 'Member' }}</span>
          </div>
        </div>
      </div>
    </header>

    <main class="dash-content">
      @yield('content')
    </main>
  </div>
</div>

<script>
document.getElementById('sidebarToggle')?.addEventListener('click', function() {
  document.getElementById('sidebar').classList.toggle('collapsed');
});
</script>

</body>
</html>
