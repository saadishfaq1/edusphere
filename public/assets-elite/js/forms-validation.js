/* Bootstrap-style needs-validation hookup */
(function () {
  'use strict';
  function bind() {
    document.querySelectorAll('.needs-validation').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        form.classList.add('was-validated');
      }, false);
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', bind);
  else bind();
  document.addEventListener('partials:ready', bind);
})();
