/* ═══════════════════════════════════════════════════
   CHURCH DIRECTORY — Main JavaScript
   ═══════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', () => {

  /* ── 1. STICKY HEADER GLASS EFFECT ─────────────── */
  const siteHeader = document.querySelector('.site-header');
  if (siteHeader) {
    window.addEventListener('scroll', () => {
      siteHeader.classList.toggle('scrolled', window.scrollY > 50);
    }, { passive: true });
  }

  /* ── 2. MOBILE NAV TOGGLE ──────────────────────── */
  const navMenu = document.querySelector('.nav-menu');
  if (navMenu) {
    const toggle = document.createElement('button');
    toggle.className = 'mobile-toggle';
    toggle.setAttribute('aria-label', 'Toggle navigation');
    toggle.innerHTML = '<span></span><span></span><span></span>';
    const navParent = navMenu.closest('nav');
    if (navParent) navParent.parentNode.insertBefore(toggle, navParent);

    toggle.addEventListener('click', () => {
      toggle.classList.toggle('open');
      navMenu.classList.toggle('open');
    });
  }

  /* ── 3. SCROLL REVEAL ───────────────────────────── */
  const revealObserver = new IntersectionObserver(
    entries => entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('visible');
        revealObserver.unobserve(e.target);
      }
    }),
    { threshold: 0.1, rootMargin: '0px 0px -40px 0px' }
  );
  document.querySelectorAll('.fade-section').forEach(el => revealObserver.observe(el));

  /* ── 4. SMOOTH ANCHOR SCROLLING ─────────────────── */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', e => {
      const target = document.querySelector(anchor.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  /* ── 5. CARD HOVER TILT ─────────────────────────── */
  document.querySelectorAll('.carousel-item, .about-card, .church-card, .pastor-card').forEach(card => {
    card.style.transition = 'transform 0.28s cubic-bezier(0.25,1,0.5,1), box-shadow 0.28s ease';
    card.addEventListener('mousemove', e => {
      const rect = card.getBoundingClientRect();
      const x = ((e.clientX - rect.left) / rect.width - 0.5) * 6;
      const y = ((e.clientY - rect.top) / rect.height - 0.5) * 6;
      card.style.transform = `translateY(-6px) rotateX(${-y}deg) rotateY(${x}deg)`;
    });
    card.addEventListener('mouseleave', () => { card.style.transform = ''; });
  });

  /* ── 6. SEARCH LIVE FILTER ──────────────────────── */
  const searchInput = document.getElementById('aba-search-input');
  if (searchInput) {
    searchInput.addEventListener('input', () => {
      const q = searchInput.value.toLowerCase().trim();
      document.querySelectorAll('.carousel-item, .church-card, .pastor-card').forEach(card => {
        const match = !q || card.textContent.toLowerCase().includes(q);
        card.style.opacity = match ? '1' : '0.2';
        card.style.transform = match ? '' : 'scale(0.96)';
        card.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
        card.style.pointerEvents = match ? '' : 'none';
      });
    });
  }

  /* ── 7. CONTACT FORM VALIDATION ─────────────────── */
  const form = document.querySelector('.aba-form');
  if (form) {
    form.querySelectorAll('input[required], textarea[required]').forEach(input => {
      input.addEventListener('blur', () => {
        const ok = input.value.trim().length > 0;
        input.style.borderColor = ok ? 'var(--aba-green)' : 'var(--aba-accent)';
        input.style.boxShadow = ok ? '0 0 0 3px rgba(74,109,85,0.1)' : '0 0 0 3px rgba(238,99,82,0.1)';
      });
    });
  }

  /* ── 8. BELIEF ITEMS ACCORDION (mobile) ─────────── */
  if (window.innerWidth < 768) {
    document.querySelectorAll('.belief-item').forEach(item => {
      const h3 = item.querySelector('h3');
      const p = item.querySelector('p');
      if (!h3 || !p) return;
      p.style.display = 'none';
      h3.style.cursor = 'pointer';
      h3.addEventListener('click', () => {
        p.style.display = p.style.display === 'none' ? '' : 'none';
      });
    });
  }

  /* ── 9. SCROLL-TO-TOP FAB ────────────────────────── */
  const fab = document.createElement('button');
  fab.setAttribute('aria-label', 'Scroll to top');
  fab.innerHTML = '<i class="bi bi-arrow-up"></i>';
  Object.assign(fab.style, {
    position: 'fixed', bottom: '32px', right: '32px',
    width: '46px', height: '46px', borderRadius: '50%',
    background: 'var(--aba-dark)', color: 'white',
    border: 'none', cursor: 'pointer',
    boxShadow: '0 6px 20px rgba(0,0,0,0.22)',
    display: 'flex', alignItems: 'center', justifyContent: 'center',
    fontSize: '1.1rem', opacity: '0',
    transform: 'translateY(12px)',
    transition: 'opacity 0.3s ease, transform 0.3s ease, background 0.2s',
    zIndex: '9000', pointerEvents: 'none'
  });
  document.body.appendChild(fab);

  window.addEventListener('scroll', () => {
    const show = window.scrollY > 400;
    fab.style.opacity = show ? '1' : '0';
    fab.style.transform = show ? 'translateY(0)' : 'translateY(12px)';
    fab.style.pointerEvents = show ? 'auto' : 'none';
  }, { passive: true });

  fab.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  fab.addEventListener('mouseenter', () => { fab.style.background = 'var(--aba-green)'; });
  fab.addEventListener('mouseleave', () => { fab.style.background = 'var(--aba-dark)'; });

  /* ── 10. PAGE FADE-IN ────────────────────────────── */
  document.body.style.opacity = '0';
  document.body.style.transition = 'opacity 0.4s ease';
  requestAnimationFrame(() => requestAnimationFrame(() => { document.body.style.opacity = '1'; }));

});


  /* ── 11. FONT-SIZE ACCESSIBILITY PANEL ───────────────────
     Side panel with A- / A / A+ so seniors can enlarge text */
  const SIZES = [16, 18, 20, 23];   // px for html font-size
  let currentSize = parseInt(localStorage.getItem('aba-font-size')) || 1; // default = 18px (index 1)

  function applyFontSize(idx) {
    document.documentElement.style.fontSize = SIZES[idx] + 'px';
    currentSize = idx;
    localStorage.setItem('aba-font-size', idx);
    // Update button states
    document.querySelectorAll('#font-size-bar button[data-size]').forEach((btn, i) => {
      btn.style.background = i === idx ? 'var(--green)' : '';
      btn.style.borderColor = i === idx ? 'var(--green)' : '';
    });
  }

  const bar = document.createElement('div');
  bar.id = 'font-size-bar';
  bar.setAttribute('aria-label', 'Text size controls');
  bar.innerHTML = `
    <span class="bar-label">Text</span>
    <button data-size="0" title="Small text"  aria-label="Small text">A-</button>
    <button data-size="1" title="Normal text" aria-label="Normal text">A</button>
    <button data-size="2" title="Large text"  aria-label="Large text">A+</button>
    <button data-size="3" title="Largest text" aria-label="Largest text">A++</button>
  `;
  document.body.appendChild(bar);

  bar.querySelectorAll('button[data-size]').forEach(btn => {
    btn.addEventListener('click', () => applyFontSize(+btn.dataset.size));
  });
  applyFontSize(currentSize);   // restore previous preference
