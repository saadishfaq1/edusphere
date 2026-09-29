(function () {
  function init() { if (window.feather) feather.replace({ width: 22, height: 22 }); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
