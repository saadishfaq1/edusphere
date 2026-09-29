/* Morris.js init */
(function () {
  function init() {
    if (typeof Morris === 'undefined') return;
    var data = [{m:'2026-01',sales:25,rent:15},{m:'2026-02',sales:38,rent:22},{m:'2026-03',sales:32,rent:18},{m:'2026-04',sales:50,rent:30},{m:'2026-05',sales:45,rent:25},{m:'2026-06',sales:65,rent:38},{m:'2026-07',sales:55,rent:32},{m:'2026-08',sales:78,rent:45}];
    if (document.getElementById('morrisLine')) Morris.Line({ element: 'morrisLine', data: data, xkey: 'm', ykeys: ['sales','rent'], labels: ['Sales','Rent'], lineColors: ['#14C38E','#6366F1'], hideHover: 'auto', resize: true });
    if (document.getElementById('morrisArea')) Morris.Area({ element: 'morrisArea', data: data, xkey: 'm', ykeys: ['sales','rent'], labels: ['Sales','Rent'], lineColors: ['#14C38E','#6366F1'], hideHover: 'auto', resize: true });
    if (document.getElementById('morrisBar')) Morris.Bar({ element: 'morrisBar', data: data, xkey: 'm', ykeys: ['sales','rent'], labels: ['Sales','Rent'], barColors: ['#14C38E','#6366F1'], hideHover: 'auto', resize: true });
    if (document.getElementById('morrisDonut')) Morris.Donut({ element: 'morrisDonut', data: [{label:'Sale',value:55},{label:'Rent',value:25},{label:'Sold',value:20}], colors: ['#14C38E','#0EA5E9','#F59E0B'], resize: true });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
