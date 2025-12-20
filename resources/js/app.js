import './bootstrap';

import '../css/app.css';

import "@hotwired/turbo";

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Update sidebar active menu after Turbo navigation so sidebar (outside frame) stays in sync
function updateSidebarActive() {
  try {
    const links = document.querySelectorAll('#sidebar-area a.menu-link[data-turbo-frame="main_frame"]');
    const currentPath = window.location.pathname;

    links.forEach(link => {
      // normalize
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

// Run on initial load
document.addEventListener('DOMContentLoaded', updateSidebarActive);
// Run after any Turbo frame load or visit
window.addEventListener('turbo:frame-load', updateSidebarActive);
window.addEventListener('turbo:load', updateSidebarActive);
window.addEventListener('turbo:render', updateSidebarActive);
