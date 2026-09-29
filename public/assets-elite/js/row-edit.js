/* ==========================================================================
   ElitePanel — Click-to-Edit table rows
   Markup contract:
     <table data-row-edit>
       <tr>
         <td data-edit="text|email|tel|number">Original Text</td>
         <td data-edit="select" data-options="A,B,C">Current</td>  ← may contain a badge
         <td>
           <button data-row-action="edit">Edit</button>
           <button data-row-action="save" hidden>Save</button>
           <button data-row-action="cancel" hidden>Cancel</button>
         </td>
       </tr>
     </table>
   ========================================================================== */
(function () {
  'use strict';

  function findRow(btn) { return btn.closest('tr'); }

  function getCellText(td) {
    /* Prefer the badge text if present, otherwise the raw text content */
    var badge = td.querySelector('.badge');
    return (badge ? badge.textContent : td.textContent).trim();
  }

  function startEdit(row) {
    if (row.classList.contains('row-editing')) return;
    row.classList.add('row-editing');

    row.querySelectorAll('[data-edit]').forEach(function (td) {
      var type = td.getAttribute('data-edit');
      var original = getCellText(td);
      td.setAttribute('data-original-html', td.innerHTML);
      td.setAttribute('data-original-value', original);

      if (type === 'select') {
        var opts = (td.getAttribute('data-options') || '').split(',').map(function (s) { return s.trim(); }).filter(Boolean);
        var sel = document.createElement('select');
        sel.className = 'form-select form-select-sm';
        opts.forEach(function (o) {
          var op = document.createElement('option');
          op.value = o; op.textContent = o;
          if (o === original) op.selected = true;
          sel.appendChild(op);
        });
        td.innerHTML = '';
        td.appendChild(sel);
        sel.focus();
      } else {
        var input = document.createElement('input');
        input.type = type || 'text';
        input.className = 'form-control form-control-sm';
        input.value = original;
        td.innerHTML = '';
        td.appendChild(input);
      }
    });

    /* Toggle action buttons */
    toggleButtons(row, true);
    /* Focus first input */
    var first = row.querySelector('[data-edit] input, [data-edit] select');
    if (first) first.focus();
  }

  function statusBadgeClass(value) {
    var v = (value || '').toLowerCase();
    if (v === 'active') return 'badge badge-soft-success';
    if (v === 'away') return 'badge badge-soft-warning';
    if (v === 'offline') return 'badge badge-soft-secondary';
    if (v === 'do not disturb') return 'badge badge-soft-danger';
    return 'badge badge-soft-primary';
  }

  function saveEdit(row) {
    row.querySelectorAll('[data-edit]').forEach(function (td) {
      var type = td.getAttribute('data-edit');
      var ctrl = td.querySelector('input, select');
      if (!ctrl) return;
      var val = ctrl.value;
      td.removeAttribute('data-original-html');
      td.removeAttribute('data-original-value');
      if (type === 'select') {
        td.innerHTML = '<span class="' + statusBadgeClass(val) + '">' + val + '</span>';
      } else {
        td.textContent = val;
      }
    });
    row.classList.remove('row-editing');
    toggleButtons(row, false);
    if (window.RTtoast) window.RTtoast('Row updated', 'success');
  }

  function cancelEdit(row) {
    row.querySelectorAll('[data-edit]').forEach(function (td) {
      var html = td.getAttribute('data-original-html');
      if (html != null) {
        td.innerHTML = html;
        td.removeAttribute('data-original-html');
        td.removeAttribute('data-original-value');
      }
    });
    row.classList.remove('row-editing');
    toggleButtons(row, false);
  }

  function toggleButtons(row, editing) {
    var edit = row.querySelector('[data-row-action="edit"]');
    var save = row.querySelector('[data-row-action="save"]');
    var cancel = row.querySelector('[data-row-action="cancel"]');
    if (edit) (editing ? edit.setAttribute('hidden', '') : edit.removeAttribute('hidden'));
    if (save) (editing ? save.removeAttribute('hidden') : save.setAttribute('hidden', ''));
    if (cancel) (editing ? cancel.removeAttribute('hidden') : cancel.setAttribute('hidden', ''));
  }

  function bind() {
    document.querySelectorAll('[data-row-edit]').forEach(function (table) {
      if (table.__rowEditBound) return;
      table.__rowEditBound = true;
      table.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-row-action]');
        if (!btn) return;
        e.preventDefault();
        var row = findRow(btn);
        var action = btn.getAttribute('data-row-action');
        if (action === 'edit') startEdit(row);
        else if (action === 'save') saveEdit(row);
        else if (action === 'cancel') cancelEdit(row);
      });
      /* Enter = save, Escape = cancel while editing */
      table.addEventListener('keydown', function (e) {
        var row = e.target.closest('tr.row-editing');
        if (!row) return;
        if (e.key === 'Enter') { e.preventDefault(); saveEdit(row); }
        else if (e.key === 'Escape') { e.preventDefault(); cancelEdit(row); }
      });
    });
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', bind);
  else bind();
  document.addEventListener('partials:ready', bind);
})();
