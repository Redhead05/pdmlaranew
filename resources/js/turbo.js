// Loads @hotwired/turbo for the Trezo (Bootstrap) app layout only.
// Kept separate from app.js so we do NOT pull in Tailwind's preflight CSS
// (app.css) which would conflict with Bootstrap on these pages.
import '@hotwired/turbo';

// Keep the sidebar (which lives OUTSIDE the turbo-frame) in sync with the
// active route after Turbo-driven navigations.
function updateSidebarActive() {
  try {
    const links = document.querySelectorAll('#sidebar-area a.menu-link[data-turbo-frame="main_frame"]');
    const currentPath = window.location.pathname;

    links.forEach(link => {
      const url = new URL(link.href, window.location.origin);
      const match = url.pathname === currentPath || currentPath.startsWith(url.pathname);

      if (match) {
        link.classList.add('active');
        const li = link.closest('.menu-item');
        if (li) li.classList.add('open');
      } else {
        link.classList.remove('active');
        const li = link.closest('.menu-item');
        if (li) li.classList.remove('open');
      }
    });
  } catch (e) {
    console.error('updateSidebarActive error', e);
  }
}

document.addEventListener('DOMContentLoaded', updateSidebarActive);
window.addEventListener('turbo:frame-load', updateSidebarActive);
window.addEventListener('turbo:load', updateSidebarActive);
window.addEventListener('turbo:render', updateSidebarActive);
