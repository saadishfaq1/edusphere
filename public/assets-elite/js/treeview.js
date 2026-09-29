(function () {
  function init() {
    document.querySelectorAll('.treeview li').forEach(function (li) {
      li.classList.add('collapsed');
      var toggle = li.querySelector(':scope > .tv-toggle');
      if (toggle && li.querySelector(':scope > ul')) {
        toggle.addEventListener('click', function (e) {
          e.stopPropagation();
          li.classList.toggle('collapsed');
        });
      }
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
