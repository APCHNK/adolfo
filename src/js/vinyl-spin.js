// Rotate the songs vinyl disc in sync with scroll position.
// iOS quirks handled here:
//   1. `scroll` events fire sparsely during momentum scroll, but `scrollY`
//      updates every frame — so we poll inside rAF instead of binding scroll.
//   2. iOS Safari shows/hides the URL bar during scroll, which changes
//      `window.innerHeight` mid-gesture. If we used innerHeight live as the
//      divisor the rotation would jolt. We cache vh at init and only refresh
//      on orientation change (with a settle delay).

export function initVinylSpin() {
  const disc = document.querySelector('.songs-vinyl__disc');
  if (!disc) return;

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const TURNS_PER_VIEWPORT = 0.6;

  let cachedVh = window.innerHeight || document.documentElement.clientHeight || 800;
  let currentDeg = 0;
  let inView = false;
  let rafId = null;

  disc.style.willChange = 'transform';
  disc.style.backfaceVisibility = 'hidden';
  disc.style.transition = 'transform 160ms cubic-bezier(0.22, 1, 0.36, 1)';

  const apply = () => {
    disc.style.transform = `translate3d(0,0,0) rotate(${currentDeg.toFixed(2)}deg)`;
  };

  const tick = () => {
    const y = window.scrollY || window.pageYOffset || 0;
    currentDeg = (y * TURNS_PER_VIEWPORT * 360) / cachedVh;
    apply();
    rafId = inView ? requestAnimationFrame(tick) : null;
  };

  const observer = new IntersectionObserver(
    ([entry]) => {
      inView = entry.isIntersecting;
      if (inView && rafId === null) {
        rafId = requestAnimationFrame(tick);
      }
    },
    { rootMargin: '50% 0px 50% 0px' }
  );
  observer.observe(disc);

  const refreshVh = () => {
    cachedVh = window.innerHeight || document.documentElement.clientHeight || cachedVh;
  };

  window.addEventListener('orientationchange', () => {
    setTimeout(refreshVh, 250);
  });

  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      const newVh = window.innerHeight || document.documentElement.clientHeight;
      if (Math.abs(newVh - cachedVh) > 250) cachedVh = newVh;
    }, 300);
  }, { passive: true });

  const y = window.scrollY || window.pageYOffset || 0;
  currentDeg = (y * TURNS_PER_VIEWPORT * 360) / cachedVh;
  apply();
}
