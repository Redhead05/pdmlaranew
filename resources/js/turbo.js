// Loads @hotwired/turbo for the Trezo (Bootstrap) app layout only.
// Kept separate from app.js so we do NOT pull in Tailwind's preflight CSS
// (app.css) which would conflict with Bootstrap on these pages.
import '@hotwired/turbo';

// Bootstrap 5 appends an open modal's `.modal-backdrop` to <body> and adds
// `modal-open` (plus inline `overflow` / `padding-right`) to <body>. Those live
// OUTSIDE the turbo-frame, so when Turbo swaps #main_frame — after a frame form
// submission or a sidebar/action link — the open modal is discarded but its
// backdrop and the scroll lock are left behind, leaving a persistent dark
// overlay over a frozen page. Clear them on every Turbo navigation.
function clearBootstrapOverlays() {
  // Modal & offcanvas backdrops are appended to <body> (outside the frame).
  document.querySelectorAll('.modal-backdrop, .offcanvas-backdrop').forEach((el) => el.remove());

  // Modal scroll-lock artifacts on <body>.
  document.body.classList.remove('modal-open');
  ['overflow', 'padding-right', 'padding-left'].forEach((prop) => {
    document.body.style.removeProperty(prop);
  });

  // Close any offcanvas left open outside the frame.
  document.querySelectorAll('.offcanvas.show').forEach((el) => {
    el.classList.remove('show');
    el.setAttribute('aria-hidden', 'true');
    el.removeAttribute('aria-modal');
    el.style.removeProperty('visibility');
  });

  // Close any dropdowns left open in the (outside-frame) header.
  document.querySelectorAll('.dropdown-menu.show').forEach((el) => el.classList.remove('show'));
  document.querySelectorAll('.dropdown-toggle.show, [data-bs-toggle="dropdown"].show').forEach((el) => {
    el.classList.remove('show');
    el.setAttribute('aria-expanded', 'false');
  });
}

// Destroy any live jQuery DataTables before Turbo swaps/replaces the DOM.
// Re-initialising DataTables on already-initialised tables throws and leaves
// blank/misaligned columns (e.g. the "Nama" column) or duplicated controls.
function destroyAllDataTables() {
  if (window.jQuery && window.jQuery.fn && window.jQuery.fn.DataTable) {
    try {
      window.jQuery.fn.dataTable.tables({ api: true }).each(function () {
        this.clear().destroy();
      });
    } catch (e) {
      // A table may already be destroyed; that is safe to ignore.
    }
  }
}

// ---------------------------------------------------------------------------
// Page DataTable (re)initialisation registry.
//
// Root cause of the "empty DataTable" bug: Turbo's PageRenderer renders the
// incoming <body> in a DETACHED node, then re-runs its inline <script>s while
// still detached (`activateNewBody` before `assignNewBody`). At that moment
// `document.getElementById` / `$('#some-id')` return null, so the page's
// `$(function(){...})` initialiser silently skips the DataTable and the table
// stays empty until a manual refresh.
//
// Each page script therefore defines a named init function, calls it directly
// (which covers the initial, non-Turbo load where the DOM is already attached)
// AND registers it here. Turbo then re-runs every registered init on
// `turbo:render` / `turbo:load` / `turbo:frame-render`, which fire only AFTER
// the new DOM has been attached — the one moment selectors actually resolve.
// Every init guards itself with a page-unique element + a `.data()` flag so
// repeated runs on the same DOM never double-initialise.
// ---------------------------------------------------------------------------
(function () {
  const inits = new Map();

  window.__registerDataTableInit = function (name, fn) {
    if (typeof name === 'string' && typeof fn === 'function') inits.set(name, fn);
  };

  function runRegisteredInits() {
    inits.forEach((fn) => {
      try {
        fn();
      } catch (e) {
        console.error('DataTable init failed:', e);
      }
    });
  }

  ['turbo:load', 'turbo:render', 'turbo:frame-render'].forEach((eventName) => {
    document.addEventListener(eventName, runRegisteredInits);
  });
})();

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

// Clear Bootstrap modal/offcanvas/dropdown leftovers on every Turbo navigation (page + frame).
['turbo:load', 'turbo:render', 'turbo:frame-render'].forEach((eventName) => {
  document.addEventListener(eventName, clearBootstrapOverlays);
});

// Destroy live DataTables before the current page/frame is swapped or cached,
// so the replacement page starts from a clean DOM and never double-inits.
['turbo:before-render', 'turbo:before-cache', 'turbo:before-frame-render'].forEach((eventName) => {
  document.addEventListener(eventName, destroyAllDataTables);
});
