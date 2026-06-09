@extends('layouts.app')
@section('title','MUST CSIT Society — Empowering Future Technologists')
@section('content')

<section class="hero" aria-labelledby="hero-title">
  <div class="container">
    <div class="hero-grid">
      <div>
        <h1 id="hero-title">Empowering the next generation of African technologists.</h1>
        <p class="lead">The CSIT Society at the Malawi University of Science and Technology brings together students of Computer Science and IT to build, learn, and lead through workshops, hackathons, research and community.</p>
        <div class="hero-cta">
          <a href="{{ route('register') }}" class="btn btn-gold btn-lg">Become a Member</a>
          <a href="{{ route('login') }}" class="btn btn-light btn-lg">Member Login</a>
        </div>
      </div>
      <div class="hero-card">
        <div class="hero-card-label">The Society at a glance</div>
        <h2>Code &middot; Connect &middot; Create</h2>
        <p>A student-led community advancing computing excellence at MUST.</p>
        <div class="hero-stats">
          <div class="hero-stat"><div class="n">240+</div><div class="l">Members</div></div>
          <div class="hero-stat"><div class="n">25</div><div class="l">Events / yr</div></div>
          <div class="hero-stat"><div class="n">12</div><div class="l">Projects</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section-alt" aria-labelledby="pillars-title">
  <div class="container">
    <div class="section-head">
      <div class="kicker">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        What we do
      </div>
      <h2 id="pillars-title">Pillars of the Society</h2>
      <p>From classroom theory to real-world systems — we equip members with the skills, networks and opportunities to thrive.</p>
    </div>
    <div class="feature-grid">
      <div class="feature-card">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        </div>
        <h3>Workshops &amp; Bootcamps</h3>
        <p>Hands-on training in web, mobile, AI, data science and cybersecurity led by peers and industry mentors.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>
        <h3>Hackathons &amp; Competitions</h3>
        <p>Annual flagship hackathon plus participation in national and pan-African coding contests.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <h3>Research &amp; Innovation</h3>
        <p>Undergraduate research circles in AI, IoT and software engineering supporting Malawi's STI agenda.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
        </div>
        <h3>Industry Engagement</h3>
        <p>Career talks, internships and partnerships with tech firms across Malawi and the SADC region.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <h3>Community Outreach</h3>
        <p>Teaching coding in secondary schools and running digital literacy drives in surrounding districts.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h3>Mentorship</h3>
        <p>Senior students and alumni guide juniors through academics, projects and career planning.</p>
      </div>
    </div>
  </div>
</section>

<section class="section" aria-labelledby="events-title">
  <div class="container">
    <div class="section-head">
      <div class="kicker">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Upcoming
      </div>
      <h2 id="events-title">Featured Events</h2>
      <p>Workshops, hackathons and talks you won't want to miss.</p>
    </div>

    <div class="event-showcase-grid">
      <div class="event-showcase-card">
        <div class="event-showcase-date-badge"><div class="d">12</div><div class="m">Jun</div></div>
        <img class="event-showcase-image" src="https://picsum.photos/seed/aiworkshop/600/400" alt="AI Workshop group photo" loading="lazy">
        <div class="event-showcase-body">
          <h3>Intro to AI Workshop</h3>
          <p>Hands-on introduction to artificial intelligence concepts, machine learning basics, and real-world applications.</p>
          <div class="event-showcase-meta">
            <span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              MUST Lab A &middot; 14:00
            </span>
            <span class="tag">Workshop</span>
          </div>
        </div>
      </div>

      <div class="event-showcase-card">
        <div class="event-showcase-date-badge"><div class="d">04</div><div class="m">Jul</div></div>
        <img class="event-showcase-image" src="https://picsum.photos/seed/hackathon2026/600/400" alt="CSIT Hackathon participants" loading="lazy">
        <div class="event-showcase-body">
          <h3>CSIT Hackathon 2026</h3>
          <p>A 48-hour build sprint where teams solve real-world challenges using technology and innovation.</p>
          <div class="event-showcase-meta">
            <span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              Main Hall &middot; 48-hour build
            </span>
            <span class="tag">Hackathon</span>
          </div>
        </div>
      </div>

      <div class="event-showcase-card">
        <div class="event-showcase-date-badge"><div class="d">21</div><div class="m">Aug</div></div>
        <img class="event-showcase-image" src="https://picsum.photos/seed/cybertalk/600/400" alt="Cybersecurity talk speakers" loading="lazy">
        <div class="event-showcase-body">
          <h3>Cybersecurity Industry Talk</h3>
          <p>Industry professionals discuss the latest threats, career paths, and opportunities in cybersecurity.</p>
          <div class="event-showcase-meta">
            <span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              Auditorium &middot; Open to all
            </span>
            <span class="tag">Talk</span>
          </div>
        </div>
      </div>
    </div>

    <div style="text-align:center;margin-top:36px">
      <a href="{{ route('register') }}" class="btn btn-primary">Join to see all events</a>
    </div>
  </div>
</section>

<section class="section section-alt" aria-labelledby="gallery-title">
  <div class="container">
    <div class="section-head">
      <div class="kicker">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Moments
      </div>
      <h2 id="gallery-title">CSIT Society Gallery</h2>
      <p>Memories from our events, workshops, hackathons and community outreach programmes.</p>
    </div>

    <div class="gallery-grid">
      <div class="gallery-item gallery-item-wide">
        <div class="gallery-placeholder">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          <span>Group Photo — 2026 Cohort</span>
        </div>
      </div>
      <div class="gallery-item">
        <div class="gallery-placeholder" style="background:linear-gradient(135deg,var(--accent-50),var(--accent-100));color:var(--accent)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
          <span>Hackathon 2025</span>
        </div>
      </div>
      <div class="gallery-item">
        <div class="gallery-placeholder" style="background:linear-gradient(135deg,var(--gold-50),#FDE68A);color:#B45309">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <span>Awards Ceremony</span>
        </div>
      </div>
      <div class="gallery-item">
        <div class="gallery-placeholder" style="background:linear-gradient(135deg,var(--primary-50),var(--primary-100));color:var(--primary)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
          <span>Industry Talk</span>
        </div>
      </div>
      <div class="gallery-item">
        <div class="gallery-placeholder" style="background:linear-gradient(135deg,#FEF2F2,#FEE2E2);color:var(--accent)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          <span>Outreach Program</span>
        </div>
      </div>
      <div class="gallery-item">
        <div class="gallery-placeholder" style="background:linear-gradient(135deg,#ECFDF5,#D1FAE5);color:#065F46">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          <span>Workshop Session</span>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="cta-section" aria-labelledby="cta-title">
  <div class="container container-narrow">
    <div class="kicker" style="color:var(--gold);font-weight:700;letter-spacing:0.18em;text-transform:uppercase;font-size:0.75rem;margin-bottom:12px">Join us</div>
    <h2 id="cta-title">Ready to be part of MUST CSIT?</h2>
    <p>Membership is open to all MUST students with an interest in computing, IT or building things that matter.</p>
    <a href="{{ route('register') }}" class="btn btn-gold btn-lg">Register Today</a>
  </div>
</section>

@endsection
