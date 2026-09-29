/* ==========================================================================
   ElitePanel Admin - Charts (ApexCharts based)
   ========================================================================== */
(function () {
  if (typeof ApexCharts === 'undefined') return;

  var primary = '#14C38E';
  var primaryLight = 'rgba(20, 195, 142, 0.15)';
  var secondary = '#6366F1';
  var warning = '#F59E0B';
  var danger = '#EF4444';
  var info = '#0EA5E9';
  var success = '#22C55E';

  var commonAxis = {
    labels: { style: { colors: '#9CA3AF', fontSize: '11px', fontFamily: 'Inter' } },
    axisBorder: { show: false },
    axisTicks: { show: false }
  };

  var fontFam = 'Inter, sans-serif';

  /* ---------- Total Properties Bar Chart ---------- */
  var totalEl = document.querySelector('#chart-total-properties');
  if (totalEl) {
    new ApexCharts(totalEl, {
      series: [{
        name: 'Properties',
        data: [180, 240, 200, 290, 220, 320, 280, 340, 290, 380, 320, 410]
      }],
      chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily: fontFam },
      plotOptions: { bar: { borderRadius: 6, columnWidth: '40%', distributed: false } },
      dataLabels: { enabled: false },
      colors: [primary],
      grid: { borderColor: '#EEF1F6', strokeDashArray: 4, padding: { left: 0, right: 0 } },
      xaxis: Object.assign({}, commonAxis, {
        categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
      }),
      yaxis: { labels: { style: { colors: '#9CA3AF', fontSize: '11px' } } },
      tooltip: { y: { formatter: function (v) { return v + ' listings'; } } }
    }).render();
  }

  /* ---------- Property Status Donut ---------- */
  var statusEl = document.querySelector('#chart-property-status');
  if (statusEl) {
    new ApexCharts(statusEl, {
      series: [55, 25, 20],
      chart: { type: 'donut', height: 230, fontFamily: fontFam },
      labels: ['For Sale', 'For Rent', 'Sold'],
      colors: [primary, info, warning],
      stroke: { width: 0 },
      legend: { show: false },
      plotOptions: { pie: { donut: { size: '72%', labels: {
        show: true, name: { show: false },
        value: { fontSize: '22px', fontWeight: 700, color: '#0F172A', offsetY: 6,
                 formatter: function (v) { return v + '%'; } },
        total: { show: true, label: 'Sale', fontSize: '12px', color: '#9CA3AF',
                 formatter: function () { return '55%'; } }
      } } } },
      dataLabels: { enabled: false }
    }).render();
  }

  /* ---------- Listings Spark Line ---------- */
  var listEl = document.querySelector('#chart-listings-spark');
  if (listEl) {
    new ApexCharts(listEl, {
      series: [{ name: 'Listings', data: [25, 35, 28, 50, 42, 70, 60, 85, 72, 95, 80] }],
      chart: { type: 'area', height: 80, sparkline: { enabled: true } },
      colors: [primary],
      stroke: { curve: 'smooth', width: 2 },
      fill: { type: 'gradient', gradient: { opacityFrom: .35, opacityTo: 0 } },
      tooltip: { fixed: { enabled: false }, x: { show: false }, marker: { show: false } }
    }).render();
  }

  /* ---------- Listings Bar Sparkline ---------- */
  var listBar = document.querySelector('#chart-listings-bar');
  if (listBar) {
    new ApexCharts(listBar, {
      series: [{ name: 'Last Week', data: [25, 32, 18, 40, 28, 36, 22, 38, 30, 45, 28, 42] }],
      chart: { type: 'bar', height: 80, sparkline: { enabled: true } },
      plotOptions: { bar: { columnWidth: '50%', borderRadius: 3 } },
      colors: [primary],
      tooltip: { fixed: { enabled: false }, x: { show: false }, marker: { show: false } }
    }).render();
  }

  /* ---------- Listings Added Sparkline (line) ---------- */
  var listAdded = document.querySelector('#chart-listings-added');
  if (listAdded) {
    new ApexCharts(listAdded, {
      series: [{ name: 'Listings Added', data: [120, 158, 142, 192, 168, 220, 198, 258, 232, 290, 274, 318] }],
      chart: { type: 'area', height: 80, sparkline: { enabled: true } },
      colors: [primary],
      stroke: { curve: 'smooth', width: 2 },
      fill: { type: 'gradient', gradient: { opacityFrom: .35, opacityTo: 0 } },
      tooltip: { fixed: { enabled: false }, x: { show: false }, marker: { show: false }, y: { formatter: function (v) { return v + ' listings'; } } }
    }).render();
  }

  /* ---------- Closed Deals Sparkline (bars) ---------- */
  var closedDeals = document.querySelector('#chart-closed-deals');
  if (closedDeals) {
    new ApexCharts(closedDeals, {
      series: [{ name: 'Closed Deals', data: [180, 220, 195, 260, 240, 290, 275, 340, 305, 380, 360, 420] }],
      chart: { type: 'bar', height: 80, sparkline: { enabled: true } },
      plotOptions: { bar: { columnWidth: '50%', borderRadius: 3 } },
      colors: [success],
      tooltip: { fixed: { enabled: false }, x: { show: false }, marker: { show: false }, y: { formatter: function (v) { return v + ' deals'; } } }
    }).render();
  }

  /* ---------- Sales line / area ---------- */
  var salesEl = document.querySelector('#chart-sales');
  if (salesEl) {
    new ApexCharts(salesEl, {
      series: [
        { name: 'This Year', data: [25, 38, 32, 50, 45, 65, 55, 78, 70, 85, 80, 95] },
        { name: 'Last Year', data: [18, 22, 28, 30, 32, 40, 38, 50, 48, 55, 58, 65] }
      ],
      chart: { type: 'area', height: 320, toolbar: { show: false }, fontFamily: fontFam },
      colors: [primary, secondary],
      stroke: { curve: 'smooth', width: 3 },
      fill: { type: 'gradient', gradient: { opacityFrom: .25, opacityTo: 0 } },
      grid: { borderColor: '#EEF1F6', strokeDashArray: 4 },
      xaxis: Object.assign({}, commonAxis, {
        categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
      }),
      yaxis: { labels: { formatter: function(v){ return '$' + v + 'k'; },
        style: { colors: '#9CA3AF', fontSize: '11px' } } },
      legend: { position: 'top', horizontalAlign: 'right', fontFamily: fontFam },
      tooltip: { y: { formatter: function(v){ return '$' + v + 'k'; } } }
    }).render();
  }

  /* ---------- Sales Trends spark area ---------- */
  var trendEl = document.querySelector('#chart-sales-trend');
  if (trendEl) {
    new ApexCharts(trendEl, {
      series: [{ name: 'Sales', data: [40, 30, 50, 38, 70, 55, 80, 65, 95, 75, 110, 90] }],
      chart: { type: 'area', height: 120, sparkline: { enabled: true } },
      colors: [primary],
      stroke: { curve: 'smooth', width: 2 },
      fill: { type: 'gradient', gradient: { opacityFrom: .35, opacityTo: 0 } }
    }).render();
  }

  /* ---------- Sale vs Rent radial ---------- */
  var radialEl = document.querySelector('#chart-sale-rent');
  if (radialEl) {
    new ApexCharts(radialEl, {
      series: [76],
      chart: { type: 'radialBar', height: 200 },
      colors: [primary],
      plotOptions: { radialBar: {
        hollow: { size: '65%' },
        track: { background: '#EEF1F6' },
        dataLabels: { name: { show: false }, value: { fontSize: '24px', fontWeight: 700, color: '#0F172A' } }
      } }
    }).render();
  }

  /* ---------- Recent Purchase mini sparkline ---------- */
  document.querySelectorAll('[data-spark]').forEach(function (el, i) {
    var color = el.getAttribute('data-spark-color') || primary;
    var data = JSON.parse(el.getAttribute('data-spark') || '[10,15,12,18,16,22,20]');
    new ApexCharts(el, {
      series: [{ data: data }],
      chart: { type: 'line', height: 40, sparkline: { enabled: true } },
      colors: [color],
      stroke: { curve: 'smooth', width: 2 },
      tooltip: { fixed: { enabled: false }, x: { show: false }, marker: { show: false } }
    }).render();
  });

  /* ---------- Revenue Forecast (Sales Dashboard) ---------- */
  var forecastEl = document.querySelector('#chart-revenue-forecast');
  if (forecastEl) {
    new ApexCharts(forecastEl, {
      series: [
        {
          name: 'Actual Revenue',
          data: [
            { x: 'Apr 1',  y: 120 },
            { x: 'Apr 5',  y: 185 },
            { x: 'Apr 10', y: 240 },
            { x: 'Apr 15', y: 320 },
            { x: 'Apr 18', y: 410 },
            { x: 'Apr 22', y: 520 },
            { x: 'Today',  y: 842 }
          ]
        },
        {
          name: 'Projected Revenue',
          data: [
            { x: 'Today',  y: 842 },
            { x: 'May 5',  y: 920 },
            { x: 'May 15', y: 1010 },
            { x: 'May 25', y: 1090 },
            { x: 'Jun 10', y: 1140 },
            { x: 'Jun 20', y: 1170 },
            { x: 'Jun 30', y: 1200 }
          ]
        }
      ],
      chart: {
        type: 'area', height: 240, fontFamily: fontFam,
        toolbar: { show: false }, zoom: { enabled: false }
      },
      colors: [primary, secondary],
      stroke: { curve: 'smooth', width: [3, 3], dashArray: [0, 6] },
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1, opacityFrom: [0.35, 0.05], opacityTo: [0, 0],
          stops: [0, 90, 100]
        }
      },
      markers: {
        size: [0, 0], hover: { size: 6 },
        strokeColors: '#fff', strokeWidth: 3, fillOpacity: 1
      },
      grid: { borderColor: '#EEF1F6', strokeDashArray: 4, padding: { left: 10, right: 10 } },
      xaxis: {
        type: 'category',
        labels: { style: { colors: '#9CA3AF', fontSize: '11px' } },
        axisBorder: { show: false }, axisTicks: { show: false }
      },
      yaxis: {
        labels: {
          formatter: function (v) { return '$' + v + 'K'; },
          style: { colors: '#9CA3AF', fontSize: '11px' }
        }
      },
      annotations: {
        yaxis: [{
          y: 1200,
          borderColor: '#F59E0B',
          strokeDashArray: 5,
          label: {
            borderColor: '#F59E0B',
            style: { color: '#fff', background: '#F59E0B', fontSize: '10px', fontWeight: 700 },
            text: 'TARGET $1.2M'
          }
        }, {
          y: 842,
          borderColor: 'rgba(20,195,142,.3)',
          strokeDashArray: 3,
          label: {
            borderColor: '#14C38E',
            style: { color: '#fff', background: '#14C38E', fontSize: '10px', fontWeight: 700 },
            text: 'TODAY $842K'
          }
        }]
      },
      legend: { show: false },
      tooltip: {
        shared: true,
        y: { formatter: function (v) { return '$' + v + 'K'; } }
      }
    }).render();
  }

  /* ---------- Generic chart from data attributes ---------- */
  document.querySelectorAll('[data-chart="line"]').forEach(function(el){
    new ApexCharts(el, {
      series: JSON.parse(el.getAttribute('data-series')),
      chart: { type: 'line', height: parseInt(el.getAttribute('data-height')||'260'),
               toolbar: { show:false }, fontFamily: fontFam },
      colors: [primary, secondary, warning],
      stroke: { curve: 'smooth', width: 3 },
      grid: { borderColor: '#EEF1F6', strokeDashArray: 4 },
      xaxis: Object.assign({}, commonAxis, { categories: JSON.parse(el.getAttribute('data-cats')||'[]') })
    }).render();
  });

  document.querySelectorAll('[data-chart="bar"]').forEach(function(el){
    new ApexCharts(el, {
      series: JSON.parse(el.getAttribute('data-series')),
      chart: { type: 'bar', height: parseInt(el.getAttribute('data-height')||'260'),
               toolbar: { show:false }, fontFamily: fontFam },
      plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
      colors: [primary, secondary, warning],
      grid: { borderColor: '#EEF1F6', strokeDashArray: 4 },
      xaxis: Object.assign({}, commonAxis, { categories: JSON.parse(el.getAttribute('data-cats')||'[]') })
    }).render();
  });

  document.querySelectorAll('[data-chart="area"]').forEach(function(el){
    new ApexCharts(el, {
      series: JSON.parse(el.getAttribute('data-series')),
      chart: { type: 'area', height: parseInt(el.getAttribute('data-height')||'260'),
               toolbar: { show:false }, fontFamily: fontFam },
      colors: [primary, secondary, warning],
      stroke: { curve: 'smooth', width: 3 },
      fill: { type: 'gradient', gradient: { opacityFrom: .35, opacityTo: 0 } },
      grid: { borderColor: '#EEF1F6', strokeDashArray: 4 },
      xaxis: Object.assign({}, commonAxis, { categories: JSON.parse(el.getAttribute('data-cats')||'[]') })
    }).render();
  });

  document.querySelectorAll('[data-chart="donut"]').forEach(function(el){
    new ApexCharts(el, {
      series: JSON.parse(el.getAttribute('data-series')),
      chart: { type: 'donut', height: parseInt(el.getAttribute('data-height')||'260'), fontFamily: fontFam },
      labels: JSON.parse(el.getAttribute('data-labels')||'[]'),
      colors: [primary, secondary, warning, info, danger],
      stroke: { width: 0 },
      plotOptions: { pie: { donut: { size: '70%' } } }
    }).render();
  });

  document.querySelectorAll('[data-chart="radialBar"]').forEach(function(el){
    new ApexCharts(el, {
      series: JSON.parse(el.getAttribute('data-series')),
      chart: { type: 'radialBar', height: parseInt(el.getAttribute('data-height')||'260') },
      colors: [primary, secondary, warning, info, danger],
      plotOptions: { radialBar: { dataLabels: { name: { show: true }, value: { show: true } } } },
      labels: JSON.parse(el.getAttribute('data-labels')||'["Total"]')
    }).render();
  });
})();
