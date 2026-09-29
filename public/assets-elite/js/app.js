/* ==========================================================================
   ElitePanel Admin - Core App JS
   ========================================================================== */
(function () {
  'use strict';

  /* ---------- Sidebar toggle ---------- */
  const body = document.body;
  const menuToggle = document.querySelector('[data-toggle="sidebar"]');
  const backdrop = document.createElement('div');
  backdrop.className = 'sidebar-backdrop';
  document.body.appendChild(backdrop);

  function isMobile() { return window.innerWidth < 992; }

  if (menuToggle) {
    menuToggle.addEventListener('click', function () {
      if (isMobile()) {
        body.classList.toggle('sidebar-open');
      } else {
        body.classList.toggle('sidebar-mini');
        var on = body.classList.contains('sidebar-mini');
        try { localStorage.setItem('rt-set-mini', on ? '1' : '0'); } catch (e) {}
      }
    });
  }

  // Clear legacy `rt-sidebar-mini` key from previous version (now using `rt-set-mini`)
  try { localStorage.removeItem('rt-sidebar-mini'); } catch (e) {}

  // restore mini state from the unified settings-panel key
  try {
    if (localStorage.getItem('rt-set-mini') === '1' && !isMobile()) {
      body.classList.add('sidebar-mini');
    }
  } catch (e) {}

  backdrop.addEventListener('click', function () { body.classList.remove('sidebar-open'); });

  /* ---------- Sidebar collapsible groups ---------- */
  document.querySelectorAll('[data-bs-toggle="collapse"][data-menu-group]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var others = document.querySelectorAll('[data-menu-group].show');
      others.forEach(function (o) {
        if (o.id !== link.getAttribute('data-bs-target').replace('#', '')) {
          var inst = bootstrap.Collapse.getInstance(o);
          if (inst) inst.hide();
        }
      });
    });
  });

  /* ---------- Sidebar mini-mode popout submenus ---------- */
  var popoutHideTimer = null;

  function isMiniMode() {
    return document.body.classList.contains('sidebar-mini') && !isMobile();
  }

  function closeAllPopouts(except) {
    document.querySelectorAll('.app-sidebar .menu-item.popout-open').forEach(function (m) {
      if (m !== except) m.classList.remove('popout-open');
    });
  }

  function openPopout(item) {
    var collapse = item.querySelector(':scope > .collapse');
    if (!collapse) return;

    closeAllPopouts(item);

    /* Inject title once */
    if (!collapse.querySelector('.popout-title')) {
      var label = item.querySelector('.menu-text');
      var name = label ? label.textContent.trim() : '';
      if (name) {
        var t = document.createElement('div');
        t.className = 'popout-title';
        t.textContent = name;
        collapse.insertBefore(t, collapse.firstChild);
      }
    }

    var rect = item.getBoundingClientRect();
    var winH = window.innerHeight;
    var GAP = 12;

    /* Render hidden first so we can measure natural height, then position. */
    collapse.style.visibility = 'hidden';
    collapse.style.top = rect.top + 'px';
    collapse.style.maxHeight = '';
    collapse.style.overflowY = '';
    item.classList.add('popout-open');

    var popH = collapse.offsetHeight;
    var maxAvail = winH - GAP * 2;
    var top;
    if (popH >= maxAvail) {
      /* Taller than viewport: pin near top with internal scroll */
      top = GAP;
      collapse.style.maxHeight = maxAvail + 'px';
      collapse.style.overflowY = 'auto';
    } else if (rect.top + popH + GAP > winH) {
      /* Would overflow bottom: shift up so it sits above viewport edge */
      top = winH - popH - GAP;
    } else {
      top = rect.top;
    }
    collapse.style.top = top + 'px';
    collapse.style.visibility = '';
  }

  function bindPopouts(root) {
    (root || document).querySelectorAll('.app-sidebar .sidebar-menu .menu-item').forEach(function (item) {
      if (item._popoutBound) return;
      item._popoutBound = true;

      item.addEventListener('mouseenter', function () {
        if (!isMiniMode()) return;
        clearTimeout(popoutHideTimer);
        if (item.querySelector(':scope > .collapse')) {
          openPopout(item);
        } else {
          closeAllPopouts();
        }
      });

      item.addEventListener('mouseleave', function () {
        if (!isMiniMode()) return;
        popoutHideTimer = setTimeout(function () {
          item.classList.remove('popout-open');
        }, 180);
      });
    });
  }

  bindPopouts();
  /* Re-bind after sidebar partial loads */
  document.addEventListener('partials:ready', function () { bindPopouts(); });

  /* Close popouts when switching out of mini mode or clicking outside */
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.app-sidebar .menu-item')) closeAllPopouts();
  });

  /* ---------- Theme toggle ---------- */
  const themeBtn = document.querySelector('[data-toggle="theme"]');
  const root = document.documentElement;
  function applyTheme(t) {
    root.setAttribute('data-theme', t);
    try { localStorage.setItem('rt-theme', t); } catch (e) {}
    document.querySelectorAll('[data-toggle="theme"] i').forEach(function (i) {
      i.className = t === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
    });
  }
  try {
    var savedTheme = localStorage.getItem('rt-theme');
    if (savedTheme) applyTheme(savedTheme);
  } catch (e) {}
  if (themeBtn) {
    themeBtn.addEventListener('click', function () {
      applyTheme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
    });
  }

  /* ---------- Bootstrap tooltip & popover init ---------- */
  if (window.bootstrap) {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
      new bootstrap.Tooltip(el);
    });
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(function (el) {
      new bootstrap.Popover(el);
    });
  }

  /* ---------- Auto active sidebar item by file path ---------- */
  try {
    var path = window.location.pathname.split('https://demo.mycreativetemplates.com/').pop() || 'index.html';
    document.querySelectorAll('.sidebar-menu a, .sidebar-submenu a').forEach(function (a) {
      var href = a.getAttribute('href');
      if (!href) return;
      if (href === path) {
        a.classList.add('active');
        var parent = a.closest('.collapse');
        if (parent) {
          parent.classList.add('show');
          var trigger = document.querySelector('[data-bs-target="#' + parent.id + '"]');
          if (trigger) {
            trigger.classList.add('active');
            trigger.setAttribute('aria-expanded', 'true');
          }
        }
      }
    });
  } catch (e) {}

  /* ---------- Toast helper ---------- */
  window.RTtoast = function (msg, type) {
    type = type || 'primary';
    var wrap = document.getElementById('rt-toast-wrap');
    if (!wrap) {
      wrap = document.createElement('div');
      wrap.id = 'rt-toast-wrap';
      wrap.className = 'toast-container position-fixed top-0 end-0 p-3';
      wrap.style.zIndex = 1090;
      document.body.appendChild(wrap);
    }
    var t = document.createElement('div');
    t.className = 'toast align-items-center border-0 bg-' + type + ' text-white';
    t.setAttribute('role', 'alert');
    t.innerHTML = '<div class="d-flex"><div class="toast-body">' + msg + '</div>'
      + '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
    wrap.appendChild(t);
    var bsT = new bootstrap.Toast(t, { delay: 3000 });
    bsT.show();
    t.addEventListener('hidden.bs.toast', function () { t.remove(); });
  };

  /* ---------- Generic confirm-action buttons ---------- */
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-confirm]');
    if (!btn) return;
    e.preventDefault();
    if (window.confirm(btn.getAttribute('data-confirm'))) {
      var row = btn.closest('tr, .removable-card, li.removable');
      if (row) row.remove();
      window.RTtoast('Item deleted', 'danger');
    }
  });

  /* ---------- Generic favorite toggle ---------- */
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-toggle="favorite"]');
    if (!btn) return;
    btn.classList.toggle('active');
    var icon = btn.querySelector('i');
    if (icon) {
      icon.classList.toggle('bi-heart');
      icon.classList.toggle('bi-heart-fill');
    }
  });

  /* ---------- Generic select all checkboxes ---------- */
  document.addEventListener('change', function (e) {
    if (e.target.matches('[data-check-all]')) {
      var target = e.target.getAttribute('data-check-all');
      document.querySelectorAll(target).forEach(function (cb) { cb.checked = e.target.checked; });
    }
  });

  /* ---------- Tab switch with state ---------- */
  document.addEventListener('shown.bs.tab', function (e) {
    var key = e.target.getAttribute('data-state-key');
    if (key) { try { localStorage.setItem('rt-tab-' + key, e.target.id); } catch(_){} }
  });

  /* ---------- Sidebar regrouping ----------
     The source sidebar HTML has all .sidebar-heading labels nested inside one big
     <ul class="sidebar-menu">. To make the horizontal layout work (and to keep the
     vertical layout visually identical), we rebuild every .sidebar-nav into a series
     of .menu-group blocks, each containing one heading + its own fresh <ul> with the
     menu-items that belong to that section. */
  function groupHorizontalMenus() {
    document.querySelectorAll('.sidebar-nav').forEach(function (nav) {
      if (nav.querySelector(':scope > .menu-group')) return;  // already grouped

      var headings = Array.prototype.slice.call(nav.querySelectorAll('.sidebar-heading'));
      if (!headings.length) return;

      // For each heading, collect all .menu-item nodes that appear AFTER it in DOM
      // order and BEFORE the next heading.
      var groups = headings.map(function (h, idx) {
        var next = headings[idx + 1];
        var items = [];
        var walker = document.createTreeWalker(nav, NodeFilter.SHOW_ELEMENT, null);
        // Move walker to the heading
        walker.currentNode = h;
        var node;
        while ((node = walker.nextNode())) {
          if (node === next) break;
          if (node.classList && node.classList.contains('menu-item')) items.push(node);
        }
        return { heading: h, items: items };
      });

      // Clear nav and re-build with proper groups
      nav.innerHTML = '';
      groups.forEach(function (g) {
        var wrap = document.createElement('div');
        wrap.className = 'menu-group';
        wrap.appendChild(g.heading);
        var ul = document.createElement('ul');
        ul.className = 'sidebar-menu';
        g.items.forEach(function (item) { ul.appendChild(item); });
        wrap.appendChild(ul);
        nav.appendChild(wrap);
      });
    });
  }
  document.addEventListener('partials:ready', groupHorizontalMenus);
  if (document.readyState !== 'loading') groupHorizontalMenus(); else document.addEventListener('DOMContentLoaded', groupHorizontalMenus);

  /* In horizontal layout on DESKTOP, clicking a parent menu-link should NOT trigger
     Bootstrap's collapse — we use pure-CSS hover for the dropdowns. On mobile the
     sidebar reverts to a vertical drawer where collapse-on-click is needed. */
  document.addEventListener('click', function (e) {
    if (!document.body.classList.contains('layout-horizontal')) return;
    if (window.innerWidth < 992) return;
    var link = e.target.closest('.app-sidebar .menu-link[data-bs-toggle="collapse"]');
    if (link) { e.preventDefault(); e.stopPropagation(); }
  }, true);

  /* When user clicks a sidebar link on mobile, close the drawer */
  document.addEventListener('click', function (e) {
    if (window.innerWidth >= 992) return;
    if (!document.body.classList.contains('sidebar-open')) return;
    var link = e.target.closest('.app-sidebar .submenu-link, .app-sidebar a.menu-link[href]:not([href="#"])');
    if (link) document.body.classList.remove('sidebar-open');
  });

  /* ---------- Theme Settings panel ---------- */
  function applyHorizontalLayout(on) {
    var attempt = function () {
      var sidebar = document.querySelector('.app-sidebar');
      var content = document.querySelector('.app-content');
      var wrapper = document.querySelector('.app-wrapper');
      if (!sidebar || !content || !wrapper) return false;
      var header  = content.querySelector('.app-header');
      // On mobile we ALWAYS keep the sidebar as the first child of .app-wrapper
      // (default left-drawer position) regardless of horizontal-mode preference.
      var moveIntoContent = on && window.innerWidth >= 992;
      if (moveIntoContent) {
        if (header && sidebar.parentNode !== content) header.insertAdjacentElement('afterend', sidebar);
      } else {
        if (sidebar.parentNode === content) wrapper.insertBefore(sidebar, wrapper.firstChild);
      }
      return true;
    };
    if (!attempt()) {
      document.addEventListener('partials:ready', function once() {
        document.removeEventListener('partials:ready', once);
        attempt();
      });
    }
  }

  /* When the viewport crosses the mobile breakpoint, re-evaluate sidebar placement
     so horizontal mode picks the correct DOM parent for the new size. */
  var _wasMobile = window.innerWidth < 992;
  window.addEventListener('resize', function () {
    var nowMobile = window.innerWidth < 992;
    if (nowMobile === _wasMobile) return;
    _wasMobile = nowMobile;
    if (document.body.classList.contains('layout-horizontal')) {
      applyHorizontalLayout(true);
      // Drawer should not stay open after going from mobile to desktop
      if (!nowMobile) document.body.classList.remove('sidebar-open');
    }
  });

  function isHorizontal() { return document.body.classList.contains('layout-horizontal'); }

  function refreshMiniDisabled() {
    var miniInput = document.querySelector('[data-rt-setting="mini"]');
    if (!miniInput) return;
    var row = miniInput.closest('.settings-toggle-row');
    if (isHorizontal()) {
      miniInput.disabled = true;
      if (row) row.classList.add('settings-row-disabled');
    } else {
      miniInput.disabled = false;
      if (row) row.classList.remove('settings-row-disabled');
    }
  }

  function applySetting(key, value) {
    if (key === 'boxed')      document.body.classList.toggle('layout-boxed', value);
    if (key === 'mini') {
      // The "collapsed sidebar" toggle has no meaning when horizontal menu is on
      if (isHorizontal()) {
        document.body.classList.remove('sidebar-mini');
        try { localStorage.setItem('rt-set-mini', '0'); } catch (e) {}
        return;
      }
      document.body.classList.toggle('sidebar-mini', value);
    }
    if (key === 'horizontal') {
      document.body.classList.toggle('layout-horizontal', value);
      applyHorizontalLayout(value);
      // Force-clear mini state when switching into horizontal
      if (value) document.body.classList.remove('sidebar-mini');
      refreshMiniDisabled();
    }
    if (key === 'rtl') {
      document.documentElement.setAttribute('dir', value ? 'rtl' : 'ltr');
    }
    try { localStorage.setItem('rt-set-' + key, value ? '1' : '0'); } catch (e) {}
  }

  function bindSettingsPanel() {
    document.querySelectorAll('[data-rt-setting]').forEach(function (input) {
      var key = input.getAttribute('data-rt-setting');
      var saved = null;
      try { saved = localStorage.getItem('rt-set-' + key); } catch (e) {}
      var isOn = (key === 'mini')
        ? document.body.classList.contains('sidebar-mini')
        : (saved === '1');
      input.checked = isOn;
      if (saved === '1') applySetting(key, true);

      input.addEventListener('change', function () { applySetting(key, input.checked); });
    });

    /* Color mode radios */
    document.querySelectorAll('input[name="rt-color-mode"]').forEach(function (radio) {
      var current = root.getAttribute('data-theme') || 'light';
      if (radio.value === current) radio.checked = true;
      radio.addEventListener('change', function () {
        if (radio.checked) applyTheme(radio.value);
      });
    });

    /* Reset */
    var reset = document.querySelector('[data-rt-reset]');
    if (reset) reset.addEventListener('click', function () {
      ['boxed','mini','horizontal','rtl'].forEach(function (k) {
        try { localStorage.removeItem('rt-set-' + k); } catch (e) {}
        applySetting(k, false);
        var inp = document.querySelector('[data-rt-setting="' + k + '"]');
        if (inp) inp.checked = false;
      });
      applyTheme('light');
      var lightRadio = document.querySelector('input[name="rt-color-mode"][value="light"]');
      if (lightRadio) lightRadio.checked = true;
    });

    refreshMiniDisabled();
  }

  document.addEventListener('partials:ready', bindSettingsPanel);
  if (document.readyState !== 'loading') bindSettingsPanel(); else document.addEventListener('DOMContentLoaded', bindSettingsPanel);

  /* ---------- Update year ---------- */
  document.querySelectorAll('[data-year]').forEach(function (el) { el.textContent = new Date().getFullYear(); });

  /* ---------- Live search ---------- */
  document.querySelectorAll('[data-list-search]').forEach(function (input) {
    var target = input.getAttribute('data-list-search');
    input.addEventListener('input', function () {
      var q = input.value.toLowerCase();
      document.querySelectorAll(target).forEach(function (item) {
        item.style.display = item.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
      });
    });
  });

  /* ---------- Form submit demo ---------- */
  document.querySelectorAll('[data-form-demo]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      window.RTtoast('Form submitted successfully', 'success');
    });
  });

  /* ---------- File-input visual ---------- */
  document.querySelectorAll('[data-file-display]').forEach(function (input) {
    input.addEventListener('change', function () {
      var label = input.parentNode.querySelector('[data-file-label]');
      if (label && input.files.length) label.textContent = input.files[0].name;
    });
  });

  /* ---------- Data-driven background images (cover photos) ---------- */
  document.querySelectorAll('[data-cover-bg]').forEach(function (el) {
    var url = el.getAttribute('data-cover-bg');
    if (url) el.style.backgroundImage = 'url(' + url + ')';
  });

  /* ---------- Data-driven progress widths ---------- */
  document.querySelectorAll('[data-width-percent]').forEach(function (el) {
    var pct = el.getAttribute('data-width-percent');
    if (pct) el.style.width = pct + '%';
  });
})();
