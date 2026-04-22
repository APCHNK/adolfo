// Replaces wow.js: reveals elements with .reveal class as they enter the viewport.
// Honors data-reveal-delay (ms) and .reveal--desktop (only runs on >=1024px).
export function initReveal() {
  const desktopOnly = window.matchMedia('(min-width: 1024px)').matches;
  const els = document.querySelectorAll('.reveal');
  if (!els.length) return;

  if (!('IntersectionObserver' in window)) {
    els.forEach(el => el.classList.add('is-visible'));
    return;
  }

  const io = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const delay = parseInt(el.dataset.revealDelay || '0', 10);
      if (delay > 0) el.style.transitionDelay = delay + 'ms';
      el.classList.add('is-visible');
      observer.unobserve(el);
    });
  }, { rootMargin: '0px 0px -80px 0px', threshold: 0.05 });

  els.forEach(el => {
    if (el.classList.contains('reveal--desktop') && !desktopOnly) {
      el.classList.add('is-visible');
      return;
    }
    io.observe(el);
  });
}
