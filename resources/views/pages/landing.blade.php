@extends('layouts.app')
@section('title','MUST CSIT Society — Empowering Future Technologists')
@section('content')

<section class="hero">
  <div class="container">
    <div>
      <h1>Empowering the next generation of African technologists.</h1>
      <p class="lead">The CSIT Society at the Malawi University of Science and Technology brings together students of Computer Science and IT to build, learn, and lead through workshops, hackathons, research and community.</p>
      <div class="cta">
        <a href="{{ route('register') }}" class="btn btn-gold">Become a Member</a>
        <a href="{{ route('login') }}" class="btn btn-outline" style="border-color:#fff;color:#fff">Member Login</a>
      </div>
    </div>
    <div class="hero-card">
      <h3>The Society at a glance</h3>
      <h2 style="color:#fff;font-size:1.4rem">Code · Connect · Create</h2>
      <p style="color:#d4dcec;margin:8px 0 0">A student-led community advancing computing excellence at MUST.</p>
      <div class="hero-stats">
        <div class="hero-stat"><div class="n">240+</div><div class="l">Members</div></div>
        <div class="hero-stat"><div class="n">25</div><div class="l">Events / yr</div></div>
        <div class="hero-stat"><div class="n">12</div><div class="l">Projects</div></div>
      </div>
    </div>
  </div>
</section>

<section class="block alt">
  <div class="container">
    <div class="section-head">
      <div class="kicker">What we do</div>
      <h2>Pillars of the Society</h2>
      <p>From classroom theory to real-world systems — we equip members with the skills, networks and opportunities to thrive.</p>
    </div>
    <div class="grid-3">
      <div class="card"><div class="icon">⚙</div><h3>Workshops & Bootcamps</h3><p>Hands-on training in web, mobile, AI, data science and cybersecurity led by peers and industry mentors.</p></div>
      <div class="card"><div class="icon">⚑</div><h3>Hackathons & Competitions</h3><p>Annual flagship hackathon plus participation in national and pan-African coding contests.</p></div>
      <div class="card"><div class="icon">◈</div><h3>Research & Innovation</h3><p>Undergraduate research circles in AI, IoT and software engineering supporting Malawi's STI agenda.</p></div>
      <div class="card"><div class="icon">⌘</div><h3>Industry Engagement</h3><p>Career talks, internships and partnerships with tech firms across Malawi and the SADC region.</p></div>
      <div class="card"><div class="icon">★</div><h3>Community Outreach</h3><p>Teaching coding in secondary schools and running digital literacy drives in surrounding districts.</p></div>
      <div class="card"><div class="icon">♦</div><h3>Mentorship</h3><p>Senior students and alumni guide juniors through academics, projects and career planning.</p></div>
    </div>
  </div>
</section>

<section class="block">
  <div class="container">
    <div class="section-head">
      <div class="kicker">Upcoming</div>
      <h2>Events on the calendar</h2>
    </div>
    <div class="event"><div class="date"><div class="d">12</div><div class="m">Jun</div></div><div><strong>Intro to AI Workshop</strong><div style="color:var(--muted);font-size:.9rem">MUST Lab A · 14:00</div></div><span class="tag">Workshop</span></div>
    <div class="event"><div class="date"><div class="d">04</div><div class="m">Jul</div></div><div><strong>CSIT Hackathon 2026</strong><div style="color:var(--muted);font-size:.9rem">Main Hall · 48-hour build</div></div><span class="tag">Hackathon</span></div>
    <div class="event"><div class="date"><div class="d">21</div><div class="m">Aug</div></div><div><strong>Cybersecurity Industry Talk</strong><div style="color:var(--muted);font-size:.9rem">Auditorium · Open to all</div></div><span class="tag">Talk</span></div>
    <div style="text-align:center;margin-top:24px"><a href="{{ route('register') }}" class="btn btn-primary">Join to see all events</a></div>
  </div>
</section>

<section class="block alt">
  <div class="container" style="text-align:center;max-width:720px">
    <div class="kicker" style="color:var(--must-red);font-weight:700;letter-spacing:.18em;text-transform:uppercase;font-size:.8rem">Join us</div>
    <h2>Ready to be part of MUST CSIT?</h2>
    <p style="color:var(--muted);font-size:1.05rem">Membership is open to all MUST students with an interest in computing, IT or building things that matter.</p>
    <div style="margin-top:20px"><a href="{{ route('register') }}" class="btn btn-gold">Register Today</a></div>
  </div>
</section>

@endsection
