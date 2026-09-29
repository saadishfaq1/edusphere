/* ==========================================================================
   ElitePanel — Interactive Vector World Map (Datamaps + D3 + topojson)
   ========================================================================== */
(function () {
  'use strict';

  /* Sales by country (ISO 3166-1 alpha-3 codes) → bucket → revenue */
  var rawSales = {
    USA: 2400000, CAN: 380000, MEX: 220000,
    GBR: 540000, FRA: 420000, DEU: 380000, ITA: 240000, ESP: 220000, NLD: 180000, CHE: 120000,
    RUS: 160000, IND: 480000, CHN: 620000, JPN: 380000, KOR: 180000, SGP: 220000, ARE: 320000, SAU: 240000,
    AUS: 280000, NZL: 90000,
    BRA: 320000, ARG: 120000, CHL: 80000,
    ZAF: 90000, EGY: 60000, NGA: 40000
  };

  /* Bucket countries by revenue tier so Datamaps can color them via fillKey */
  function bucket(v) {
    if (v >= 1000000) return 'tier5';
    if (v >= 500000) return 'tier4';
    if (v >= 250000) return 'tier3';
    if (v >= 100000) return 'tier2';
    return 'tier1';
  }
  var data = {};
  Object.keys(rawSales).forEach(function (k) {
    data[k] = { fillKey: bucket(rawSales[k]), revenue: rawSales[k] };
  });

  var bubbles = [
    { name: 'Hillside Estate · $680K',     latitude: 37.7749, longitude: -122.4194, radius: 6, fillKey: 'marker' },
    { name: 'Skyline Tower · $2.1M',       latitude: 40.7128, longitude: -74.0060,  radius: 7, fillKey: 'marker' },
    { name: 'Ocean Crest · $520K',         latitude: 25.7617, longitude: -80.1918,  radius: 6, fillKey: 'marker' },
    { name: 'The Pearl · $540K',           latitude: 51.5074, longitude: -0.1278,   radius: 6, fillKey: 'marker' },
    { name: 'Marine Heights · $480K',      latitude: 19.0760, longitude: 72.8777,   radius: 6, fillKey: 'marker' },
    { name: 'Palm Tower · $320K',          latitude: 25.2769, longitude: 55.2962,   radius: 6, fillKey: 'marker' },
    { name: 'Sakura Loft · $380K',         latitude: 35.6762, longitude: 139.6503,  radius: 6, fillKey: 'marker' },
    { name: 'Harbour View · $280K',        latitude: -33.8688, longitude: 151.2093, radius: 6, fillKey: 'marker' },
    { name: 'Jardins · $320K',             latitude: -23.5505, longitude: -46.6333, radius: 6, fillKey: 'marker' }
  ];

  var initialized = false;
  var mapInstance = null;

  function isDark() { return document.documentElement.getAttribute('data-theme') === 'dark'; }

  function build() {
    if (initialized) return true;
    var el = document.getElementById('worldMap');
    if (!el) return false;
    if (typeof Datamap === 'undefined' || typeof d3 === 'undefined' || typeof topojson === 'undefined') return false;

    initialized = true;
    el.innerHTML = '';

    var dark = isDark();
    var defaultFill = dark ? '#1B2740' : '#E8ECF2';
    var borderColor = dark ? '#0B1120' : '#FFFFFF';
    var hlBorder    = dark ? '#0B1120' : '#FFFFFF';

    mapInstance = new Datamap({
      element: el,
      responsive: true,
      projection: 'mercator',
      fills: {
        defaultFill: defaultFill,
        tier5: '#065F46',
        tier4: '#0EA475',
        tier3: '#14C38E',
        tier2: '#34D399',
        tier1: '#A7F3D0',
        marker: '#F59E0B'
      },
      data: data,
      geographyConfig: {
        borderColor: borderColor,
        borderWidth: 0.5,
        highlightFillColor: '#14C38E',
        highlightBorderColor: hlBorder,
        highlightBorderWidth: 1,
        popupOnHover: true,
        popupTemplate: function (geo, d) {
          var rev = d ? '<div class="dm-tip-row">Revenue: <strong>$' + (d.revenue / 1000).toFixed(0) + 'K</strong></div>'
                      : '<div class="dm-tip-row text-muted-soft">No deals yet</div>';
          return '<div class="dm-tip"><div class="dm-tip-name">' + geo.properties.name + '</div>' + rev + '</div>';
        }
      }
    });

    mapInstance.bubbles(bubbles, {
      borderColor: '#FFFFFF',
      borderWidth: 2,
      fillOpacity: 0.95,
      highlightFillColor: '#EF4444',
      highlightBorderColor: '#FFFFFF',
      highlightBorderWidth: 2,
      popupOnHover: true,
      popupTemplate: function (geo, d) {
        return '<div class="dm-tip"><div class="dm-tip-name"><i class="bi bi-geo-alt-fill text-warning"></i> ' + d.name + '</div></div>';
      }
    });

    /* Datamaps doesn't auto-resize — re-render on window resize (debounced) */
    var resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        if (mapInstance && mapInstance.resize) mapInstance.resize();
      }, 200);
    });

    return true;
  }

  /* Retry every 80ms (max 50 tries = 4s) until all libs are loaded */
  function retryInit() {
    if (build()) return;
    var attempts = 0;
    var t = setInterval(function () {
      attempts++;
      if (build() || attempts >= 50) clearInterval(t);
    }, 80);
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', retryInit);
  else retryInit();
  document.addEventListener('partials:ready', retryInit);
  window.addEventListener('load', retryInit);

  /* Re-render map when theme toggles so country fills/borders update */
  try {
    var obs = new MutationObserver(function (muts) {
      for (var i = 0; i < muts.length; i++) {
        if (muts[i].attributeName === 'data-theme' && initialized) {
          initialized = false;
          mapInstance = null;
          var el = document.getElementById('worldMap');
          if (el) el.innerHTML = '';
          retryInit();
          break;
        }
      }
    });
    obs.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
  } catch (e) {}
})();
