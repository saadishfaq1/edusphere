(function () {
  function init() {
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-anim]');
      if (!btn) return;
      var anim = btn.getAttribute('data-anim');
      var targets = [document.getElementById('animTarget'), document.getElementById('animTarget2')].filter(Boolean);
      targets.forEach(function (t) {
        t.classList.remove('animate__animated');
        Array.from(t.classList).forEach(function (c) { if (c.indexOf('animate__') === 0) t.classList.remove(c); });
        void t.offsetWidth;
        t.classList.add('animate__animated', 'animate__' + anim);
      });
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
