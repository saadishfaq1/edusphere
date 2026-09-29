/* Flot init */
(function () {
  var charts = {};
  var built = false;

  function isDark() { return document.documentElement.getAttribute('data-theme') === 'dark'; }
  function gridColor() { return isDark() ? 'rgba(255,255,255,.08)' : '#E8ECF2'; }

  function build() {
    if (typeof jQuery === 'undefined' || typeof jQuery.plot === 'undefined') return;
    built = true;

    var primary = '#14C38E', sec = '#6366F1', warn = '#F59E0B', info = '#0EA5E9';
    var data1 = [['Jan',25],['Feb',38],['Mar',32],['Apr',50],['May',45],['Jun',65],['Jul',55],['Aug',78]];
    var data2 = [['Jan',12],['Feb',22],['Mar',18],['Apr',30],['May',25],['Jun',38],['Jul',32],['Aug',45]];
    var gc = gridColor();

    if (jQuery('#flotLine').length) charts.line = jQuery.plot('#flotLine', [
      { data: data1, label: 'Sales', color: primary, lines: { show: true, lineWidth: 2.5 }, points: { show: true, radius: 3 } },
      { data: data2, label: 'Rent',  color: sec,    lines: { show: true, lineWidth: 2.5 }, points: { show: true, radius: 3 } }
    ], {
      xaxis: { mode: 'categories', tickLength: 0 },
      yaxis: { tickLength: 0 },
      grid: { borderWidth: 0, color: gc, hoverable: true }
    });

    if (jQuery('#flotBar').length) charts.bar = jQuery.plot('#flotBar', [
      { data: data1, color: primary, bars: { show: true, barWidth: .6, align: 'center', fill: 1, lineWidth: 0 } }
    ], {
      xaxis: { mode: 'categories', tickLength: 0 },
      yaxis: { tickLength: 0 },
      grid: { borderWidth: 0, color: gc }
    });

    if (jQuery('#flotPie').length) charts.pie = jQuery.plot('#flotPie', [
      { label: 'House',     data: 42, color: primary },
      { label: 'Villa',     data: 18, color: sec },
      { label: 'Apartment', data: 28, color: warn },
      { label: 'Studio',    data: 12, color: info }
    ], {
      series: { pie: { show: true, innerRadius: 0.4, label: { show: false } } },
      legend: { show: true }
    });

    if (jQuery('#flotArea').length) charts.area = jQuery.plot('#flotArea', [
      { data: data1, color: primary, lines: { show: true, fill: 0.3, lineWidth: 2.5 } }
    ], {
      xaxis: { mode: 'categories', tickLength: 0 },
      yaxis: { tickLength: 0 },
      grid: { borderWidth: 0, color: gc }
    });

    /* Re-render on resize */
    if (window.jQuery) jQuery(window).on('resize.flot', function () {
      Object.keys(charts).forEach(function (k) {
        try { charts[k].resize(); charts[k].setupGrid(); charts[k].draw(); } catch (e) {}
      });
    });
  }

  function rebuild() {
    /* Tear down old plots so they redraw with the new theme color */
    Object.keys(charts).forEach(function (k) {
      try { charts[k].shutdown && charts[k].shutdown(); } catch (e) {}
      var el = charts[k].getPlaceholder && charts[k].getPlaceholder();
      if (el && el[0]) el[0].innerHTML = '';
    });
    charts = {};
    build();
  }

  function tryInit() {
    requestAnimationFrame(function () {
      if (!built) build();
    });
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', tryInit);
  else tryInit();
  document.addEventListener('partials:ready', tryInit);

  /* Watch for theme toggle via [data-theme] attribute changes on <html> */
  try {
    var obs = new MutationObserver(function (muts) {
      for (var i = 0; i < muts.length; i++) {
        if (muts[i].attributeName === 'data-theme' && built) { rebuild(); break; }
      }
    });
    obs.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
  } catch (e) {}
})();
