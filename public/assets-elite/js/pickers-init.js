/* Flatpickr init */
(function () {
  function init() {
    if (typeof flatpickr === 'undefined') return;
    document.querySelectorAll('[data-flatpickr]').forEach(function (el) { flatpickr(el, {}); });
    document.querySelectorAll('[data-flatpickr-range]').forEach(function (el) { flatpickr(el, { mode: 'range' }); });
    document.querySelectorAll('[data-flatpickr-multi]').forEach(function (el) { flatpickr(el, { mode: 'multiple' }); });
    document.querySelectorAll('[data-flatpickr-time]').forEach(function (el) { flatpickr(el, { enableTime: true, noCalendar: true, dateFormat: 'H:i' }); });
    document.querySelectorAll('[data-flatpickr-datetime]').forEach(function (el) { flatpickr(el, { enableTime: true, dateFormat: 'Y-m-d H:i' }); });
    document.querySelectorAll('[data-flatpickr-inline]').forEach(function (el) { flatpickr(el, { inline: true }); });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
