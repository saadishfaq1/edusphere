/* Leaflet maps init */
(function () {
  function init() {
    if (typeof L === 'undefined') return;
    var props = [
      { lat: 37.78, lng: -122.41, name: 'Hillside Premium Estate', price: '$680,000' },
      { lat: 37.79, lng: -122.40, name: 'Sunlit Studio Apartment', price: '$1,250,000' },
      { lat: 37.77, lng: -122.42, name: 'Lakeview Modern Villa', price: '$2,450/mo' },
      { lat: 37.80, lng: -122.41, name: 'Greenwood Hills', price: '$615,000' }
    ];
    if (document.getElementById('leafletMain')) {
      var m = L.map('leafletMain').setView([37.78, -122.41], 13);
      L.tileLayer('../../../%7bs%7d.tile.openstreetmap.org/%7bz%7d/%7bx%7d/%7by%7d.png', { attribution: '© OpenStreetMap' }).addTo(m);
      props.forEach(function (p) {
        L.marker([p.lat, p.lng]).addTo(m).bindPopup('<strong>' + p.name + '</strong><br>' + p.price);
      });
    }
    if (document.getElementById('leafletSingle')) {
      var s = L.map('leafletSingle').setView([40.7128, -74.0060], 13);
      L.tileLayer('../../../%7bs%7d.tile.openstreetmap.org/%7bz%7d/%7bx%7d/%7by%7d.png', { attribution: '© OpenStreetMap' }).addTo(s);
      L.marker([40.7128, -74.0060]).addTo(s).bindPopup('Skyline Tower 12B<br>$2,100,000').openPopup();
    }
    if (document.getElementById('leafletSat')) {
      var sat = L.map('leafletSat').setView([34.0522, -118.2437], 12);
      L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri' }).addTo(sat);
      L.marker([34.0522, -118.2437]).addTo(sat).bindPopup('Palm Heights Residency');
    }
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
