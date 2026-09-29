/* Choices.js init + custom-styled select handlers */
(function () {
  function init() {
    /* Choices.js */
    if (typeof Choices !== 'undefined') {
      document.querySelectorAll('[data-choices]').forEach(function (el) {
        new Choices(el, { searchEnabled: true, itemSelectText: '', shouldSort: false });
      });
    }

    /* Color-coded status select — swap class based on selected option */
    document.querySelectorAll('.cs-status').forEach(function (sel) {
      var apply = function () {
        var opt = sel.options[sel.selectedIndex];
        var cls = opt && opt.getAttribute('data-cls');
        sel.classList.remove('cs-success','cs-warning','cs-info','cs-danger','cs-secondary');
        if (cls) sel.classList.add('cs-' + cls);
      };
      sel.addEventListener('change', apply);
      apply();
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
  document.addEventListener('partials:ready', init);
})();
