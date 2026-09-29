/* ==========================================================================
   ElitePanel — Form Wizard step navigator
   ========================================================================== */
(function () {
  'use strict';

  function getStep(wizard) { return parseInt(wizard.getAttribute('data-current') || '1', 10); }
  function getTotal(wizard) { return parseInt(wizard.getAttribute('data-total') || '1', 10); }

  function showStep(wizard, n) {
    var total = getTotal(wizard);
    if (n < 1 || n > total) return;

    /* Toggle step content panels */
    wizard.querySelectorAll('.wiz-step').forEach(function (el) {
      var s = parseInt(el.getAttribute('data-step'), 10);
      if (s === n) el.removeAttribute('hidden');
      else el.setAttribute('hidden', '');
    });

    /* Update step indicator (active + completed) */
    wizard.querySelectorAll('.wizard-steps li').forEach(function (li) {
      var s = parseInt(li.getAttribute('data-step'), 10);
      li.classList.remove('active', 'completed');
      if (s < n) li.classList.add('completed');
      else if (s === n) li.classList.add('active');
    });

    /* Update buttons */
    var prev = wizard.querySelector('[data-wiz-prev]');
    var next = wizard.querySelector('[data-wiz-next]');
    var submit = wizard.querySelector('[data-wiz-submit]');
    if (prev) prev.disabled = n <= 1;
    if (n === total) {
      if (next) next.setAttribute('hidden', '');
      if (submit) submit.removeAttribute('hidden');
    } else {
      if (next) next.removeAttribute('hidden');
      if (submit) submit.setAttribute('hidden', '');
    }

    wizard.setAttribute('data-current', String(n));

    /* When entering review step, copy values from earlier inputs */
    if (n === total) populateReview(wizard);

    /* Smooth scroll to wizard top within card body */
    var card = wizard.closest('.card-body') || wizard;
    if (card.scrollTop) card.scrollTop = 0;
  }

  function populateReview(wizard) {
    /* Map: data-rev="key" → look up element by id wiz-{key} */
    wizard.querySelectorAll('[data-rev]').forEach(function (el) {
      var key = el.getAttribute('data-rev');
      var src = wizard.querySelector('#wiz-' + key);
      if (!src) return;
      var v = src.value || '';
      if (key === 'price' && v) v = '$' + Number(v).toLocaleString();
      el.textContent = v;
    });
  }

  function bindWizard(wizard) {
    var next = wizard.querySelector('[data-wiz-next]');
    var prev = wizard.querySelector('[data-wiz-prev]');
    var submit = wizard.querySelector('[data-wiz-submit]');
    var draft = wizard.querySelector('[data-wiz-draft]');

    if (next) next.addEventListener('click', function (e) {
      e.preventDefault();
      showStep(wizard, getStep(wizard) + 1);
    });
    if (prev) prev.addEventListener('click', function (e) {
      e.preventDefault();
      showStep(wizard, getStep(wizard) - 1);
    });
    if (submit) submit.addEventListener('click', function (e) {
      e.preventDefault();
      if (window.RTtoast) window.RTtoast('Wizard completed successfully', 'success');
      else alert('Wizard completed successfully');
    });
    if (draft) draft.addEventListener('click', function (e) {
      e.preventDefault();
      if (window.RTtoast) window.RTtoast('Draft saved', 'info');
    });

    /* Click on step number to jump (only to completed/current steps) */
    wizard.querySelectorAll('.wizard-steps li[data-step]').forEach(function (li) {
      li.style.cursor = 'pointer';
      li.addEventListener('click', function () {
        var target = parseInt(li.getAttribute('data-step'), 10);
        var current = getStep(wizard);
        /* allow going back freely, forward only one step */
        if (target <= current + 1) showStep(wizard, target);
      });
    });

    /* Initialize visible state */
    showStep(wizard, getStep(wizard));
  }

  function init() {
    document.querySelectorAll('[data-wizard]').forEach(bindWizard);
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
  document.addEventListener('partials:ready', init);
})();
