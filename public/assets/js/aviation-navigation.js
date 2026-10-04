(() => {
  const toggle = document.getElementById('av-menu-toggle');
  const sidebar = document.getElementById('av-sidebar');
  const scrim = document.getElementById('av-nav-scrim');
  if (!toggle || !sidebar || !scrim) return;
  const setOpen = open => {
    sidebar.classList.toggle('open', open);
    scrim.classList.toggle('open', open);
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
  };
  toggle.addEventListener('click', () => setOpen(!sidebar.classList.contains('open')));
  scrim.addEventListener('click', () => setOpen(false));
  document.addEventListener('keydown', event => { if (event.key === 'Escape') setOpen(false); });
})();
