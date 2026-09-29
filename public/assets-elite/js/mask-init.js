/* IMask init */
(function () {
  function init() {
    if (typeof IMask === 'undefined') return;
    document.querySelectorAll('[data-mask]').forEach(function (el) {
      IMask(el, { mask: el.getAttribute('data-mask') });
    });
    document.querySelectorAll('[data-mask-money]').forEach(function (el) {
      IMask(el, { mask: Number, scale: 2, thousandsSeparator: ',', radix: '.', mapToRadix: ['.'] });
    });
    document.querySelectorAll('[data-mask-num]').forEach(function (el) {
      IMask(el, { mask: Number, thousandsSeparator: ',', radix: '.' });
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
