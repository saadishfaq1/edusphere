/* ==========================================================================
   ElitePanel — Lightweight Data Table (filter + sort + paginate)
   Markup contract:
     <div class="card" data-dt>
       <input data-dt-search>
       <select data-dt-filter="type">…</select>
       <select data-dt-filter="status">…</select>
       <select data-dt-sort>…</select>            ← values like "price-asc"
       <select data-dt-per-page>…</select>
       <table><tbody>
         <tr data-name data-type data-status data-price data-date>…</tr>
       </tbody></table>
       <div data-dt-info></div>
       <ul data-dt-pagination></ul>
     </div>
   ========================================================================== */
(function () {
  'use strict';

  function initTable(card) {
    var tbody = card.querySelector('tbody');
    if (!tbody) return;
    var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
    if (!rows.length) return;

    var search = card.querySelector('[data-dt-search]');
    var filterEls = card.querySelectorAll('[data-dt-filter]');
    var sortEl = card.querySelector('[data-dt-sort]');
    var perPageEl = card.querySelector('[data-dt-per-page]');
    var info = card.querySelector('[data-dt-info]');
    var pagination = card.querySelector('[data-dt-pagination]');

    var state = {
      search: '',
      filters: {},
      filterModes: {},
      dateRange: '',
      sort: sortEl ? sortEl.value : '',
      perPage: perPageEl ? parseInt(perPageEl.value, 10) : (rows.length || 10),
      page: 1
    };

    function matches(row) {
      if (state.search) {
        var hay = row.textContent.toLowerCase();
        if (hay.indexOf(state.search.toLowerCase()) === -1) return false;
      }
      for (var key in state.filters) {
        var val = state.filters[key];
        if (!val) continue;
        /* Date "from" filter — match rows with date >= val */
        if (key === 'date' && state.filterModes && state.filterModes.date === 'from') {
          var rowDate = row.getAttribute('data-date');
          if (!rowDate) return false;
          if (new Date(rowDate).getTime() < new Date(val).getTime()) return false;
          continue;
        }
        var cellVal = row.getAttribute('data-' + key) || '';
        if (cellVal.toLowerCase() !== val.toLowerCase()) return false;
      }
      /* Date-range chip filter */
      if (state.dateRange) {
        var rowDateStr = row.getAttribute('data-date');
        if (!rowDateStr) return false;
        var rd = new Date(rowDateStr);
        var now = new Date();
        if (state.dateRange === 'today') {
          if (rd.toDateString() !== now.toDateString()) return false;
        } else if (state.dateRange === 'week') {
          var weekAgo = new Date(now); weekAgo.setDate(now.getDate() - 7);
          if (rd < weekAgo || rd > now) return false;
        }
      }
      return true;
    }

    function compare(sortKey) {
      var parts = (sortKey || '').split('-');
      var key = parts[0];
      var dir = parts[1] === 'desc' ? -1 : 1;
      return function (a, b) {
        var av = a.getAttribute('data-' + key) || '';
        var bv = b.getAttribute('data-' + key) || '';
        if (key === 'price') { av = parseFloat(av); bv = parseFloat(bv); }
        else if (key === 'date') { av = new Date(av).getTime(); bv = new Date(bv).getTime(); }
        else { av = av.toLowerCase(); bv = bv.toLowerCase(); }
        if (av < bv) return -1 * dir;
        if (av > bv) return 1 * dir;
        return 0;
      };
    }

    function render() {
      var filtered = rows.filter(matches);
      if (state.sort) filtered.sort(compare(state.sort));

      var total = filtered.length;
      var perPage = state.perPage || total || 1;
      var totalPages = Math.max(1, Math.ceil(total / perPage));
      if (state.page > totalPages) state.page = totalPages;
      var start = (state.page - 1) * perPage;
      var end = start + perPage;

      /* Detach all rows, then re-append visible slice */
      rows.forEach(function (r) { r.style.display = 'none'; });
      var slice = filtered.slice(start, end);
      slice.forEach(function (r, i) {
        r.style.display = '';
        /* keep DOM order to match sort */
        tbody.appendChild(r);
      });

      /* Info */
      if (info) {
        if (total === 0) info.textContent = 'No matching entries';
        else info.textContent = 'Showing ' + (start + 1) + '–' + Math.min(end, total) + ' of ' + total + ' entries';
      }

      /* Pagination */
      if (pagination) {
        pagination.innerHTML = '';
        var addPage = function (label, page, opts) {
          opts = opts || {};
          var li = document.createElement('li');
          li.className = 'page-item' + (opts.disabled ? ' disabled' : '') + (opts.active ? ' active' : '');
          var a = document.createElement('a');
          a.className = 'page-link'; a.href = '#'; a.innerHTML = label;
          a.addEventListener('click', function (e) {
            e.preventDefault();
            if (opts.disabled || opts.active) return;
            state.page = page;
            render();
          });
          li.appendChild(a);
          pagination.appendChild(li);
        };

        addPage('&lsaquo;', state.page - 1, { disabled: state.page === 1 });

        var maxPages = 7;
        var pages = [];
        if (totalPages <= maxPages) {
          for (var i = 1; i <= totalPages; i++) pages.push(i);
        } else {
          pages.push(1);
          var s = Math.max(2, state.page - 1);
          var e = Math.min(totalPages - 1, state.page + 1);
          if (s > 2) pages.push('…');
          for (var j = s; j <= e; j++) pages.push(j);
          if (e < totalPages - 1) pages.push('…');
          pages.push(totalPages);
        }
        pages.forEach(function (p) {
          if (p === '…') addPage('…', 0, { disabled: true });
          else addPage(String(p), p, { active: p === state.page });
        });

        addPage('&rsaquo;', state.page + 1, { disabled: state.page === totalPages });
      }
    }

    /* Wire up controls */
    if (search) search.addEventListener('input', function () {
      state.search = search.value || '';
      state.page = 1; render();
    });

    filterEls.forEach(function (f) {
      var key = f.getAttribute('data-dt-filter');
      var mode = f.getAttribute('data-dt-filter-mode');
      if (mode) state.filterModes[key] = mode;
      var evt = (f.tagName === 'INPUT' && (f.type === 'date' || f.type === 'text')) ? 'input' : 'change';
      f.addEventListener(evt, function () {
        state.filters[key] = f.value || '';
        state.page = 1; render();
      });
    });

    /* Quick filter chips */
    var chipGroup = card.querySelector('[data-dt-chip-group]');
    if (chipGroup) {
      chipGroup.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-dt-chip]');
        if (!btn) return;
        e.preventDefault();
        chipGroup.querySelectorAll('[data-dt-chip]').forEach(function (b) {
          b.classList.remove('btn-primary');
          b.classList.add('btn-light-bordered');
          b.removeAttribute('data-dt-active');
        });
        btn.classList.remove('btn-light-bordered');
        btn.classList.add('btn-primary');
        btn.setAttribute('data-dt-active', '');

        var spec = btn.getAttribute('data-dt-chip');
        /* Reset chip-driven state */
        state.dateRange = '';
        if (state.filters.status) state.filters.status = '';

        if (spec === '*') {
          /* All — clear */
        } else {
          var parts = spec.split(':');
          var k = parts[0]; var v = parts.slice(1).join(':');
          if (k === 'date-range') state.dateRange = v;
          else state.filters[k] = v;
        }
        state.page = 1;
        render();
      });
    }

    /* Clear all filters button */
    var clearBtn = card.querySelector('[data-dt-clear]');
    if (clearBtn) clearBtn.addEventListener('click', function (e) {
      e.preventDefault();
      state.search = '';
      state.filters = {};
      state.dateRange = '';
      state.page = 1;
      if (search) search.value = '';
      filterEls.forEach(function (f) {
        if (f.tagName === 'SELECT') f.selectedIndex = 0;
        else f.value = '';
      });
      if (chipGroup) {
        chipGroup.querySelectorAll('[data-dt-chip]').forEach(function (b) {
          b.classList.remove('btn-primary');
          b.classList.add('btn-light-bordered');
          b.removeAttribute('data-dt-active');
        });
        var allBtn = chipGroup.querySelector('[data-dt-chip="*"]');
        if (allBtn) {
          allBtn.classList.remove('btn-light-bordered');
          allBtn.classList.add('btn-primary');
          allBtn.setAttribute('data-dt-active', '');
        }
      }
      render();
    });

    if (sortEl) sortEl.addEventListener('change', function () {
      state.sort = sortEl.value;
      render();
    });

    if (perPageEl) perPageEl.addEventListener('change', function () {
      state.perPage = parseInt(perPageEl.value, 10) || rows.length;
      state.page = 1; render();
    });

    render();
  }

  function init() {
    document.querySelectorAll('[data-dt]').forEach(initTable);
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
  document.addEventListener('partials:ready', init);
})();
