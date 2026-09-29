/* Chart.js examples */
(function () {
  function init() {
    if (typeof Chart === 'undefined') return;
    Chart.defaults.font.family = 'Inter, sans-serif';
    Chart.defaults.color = '#6B7280';
    var primary = '#14C38E', sec = '#6366F1', warn = '#F59E0B', dang = '#EF4444', info = '#0EA5E9', purple = '#8B5CF6';
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep'];
    var line = document.getElementById('cjsLine');
    if (line) new Chart(line, { type: 'line', data: { labels: months, datasets: [
      { label: 'Sales', data: [25,38,32,50,45,65,55,78,70], borderColor: primary, backgroundColor: 'rgba(20,195,142,.1)', tension: .35, fill: true },
      { label: 'Rent', data: [12,22,18,30,25,38,32,45,40], borderColor: sec, backgroundColor: 'rgba(99,102,241,.1)', tension: .35, fill: true }
    ]}, options: { responsive: true, plugins: { legend: { position: 'top' } } } });
    var bar = document.getElementById('cjsBar');
    if (bar) new Chart(bar, { type: 'bar', data: { labels: months, datasets: [{ label: 'Listings', data: [120,150,180,140,210,260,230,280,310], backgroundColor: primary, borderRadius: 6 }]}, options: { responsive: true } });
    var pie = document.getElementById('cjsPie');
    if (pie) new Chart(pie, { type: 'pie', data: { labels: ['House','Villa','Apartment','Studio'], datasets: [{ data: [42,18,28,12], backgroundColor: [primary, sec, warn, info] }]}, options: { responsive: true } });
    var donut = document.getElementById('cjsDonut');
    if (donut) new Chart(donut, { type: 'doughnut', data: { labels: ['Sale','Rent','Sold','Pending'], datasets: [{ data: [55,25,15,5], backgroundColor: [primary, info, warn, dang] }]}, options: { responsive: true } });
    var polar = document.getElementById('cjsPolar');
    if (polar) new Chart(polar, { type: 'polarArea', data: { labels: ['Q1','Q2','Q3','Q4','Annual'], datasets: [{ data: [11,16,7,14,10], backgroundColor: [primary, sec, warn, info, purple] }]}, options: { responsive: true } });
    var radar = document.getElementById('cjsRadar');
    if (radar) new Chart(radar, { type: 'radar', data: { labels: ['Listings','Deals','Reviews','Response','Network','Closing'], datasets: [
      { label: 'Sophia', data: [85, 90, 95, 80, 75, 92], borderColor: primary, backgroundColor: 'rgba(20,195,142,.2)' },
      { label: 'Team Avg', data: [70, 75, 80, 70, 65, 75], borderColor: sec, backgroundColor: 'rgba(99,102,241,.2)' }
    ]}, options: { responsive: true } });
    var mixed = document.getElementById('cjsMixed');
    if (mixed) new Chart(mixed, { data: { labels: months, datasets: [
      { type: 'bar', label: 'Listings', data: [40, 55, 45, 65, 50, 70, 55, 80, 65], backgroundColor: primary, borderRadius: 6 },
      { type: 'line', label: 'Conversion', data: [10, 18, 12, 22, 15, 28, 20, 32, 25], borderColor: warn, backgroundColor: 'rgba(245,158,11,.2)', tension: .35 }
    ]}, options: { responsive: true } });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
