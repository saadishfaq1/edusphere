(function () {
  function init() {
    if (typeof toastr === 'undefined') return;
    toastr.options = { closeButton: true, progressBar: false, positionClass: 'toast-top-right', timeOut: 3500 };
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-toast]');
      if (btn) {
        var t = btn.getAttribute('data-toast');
        if (t === 'success') toastr.success('Property listing saved.');
        else if (t === 'info') toastr.info('3 new leads imported.');
        else if (t === 'warning') toastr.warning('Listing expires in 7 days.');
        else if (t === 'error') toastr.error('Could not save changes.');
        else if (t === 'success-title') toastr.success('Hillside Estate is live.', 'Listing Published');
        else if (t === 'info-title') toastr.info('Tap to view all.', '5 new messages');
        else if (t === 'progress') { toastr.options.progressBar = true; toastr.success('With progress bar', 'Saved'); toastr.options.progressBar = false; }
        else if (t === 'sticky') { toastr.options.timeOut = 0; toastr.options.extendedTimeOut = 0; toastr.warning('Click X to dismiss', 'Sticky toast'); toastr.options.timeOut = 3500; toastr.options.extendedTimeOut = 1000; }
      }
      var pos = e.target.closest('[data-toast-pos]');
      if (pos) {
        toastr.options.positionClass = pos.getAttribute('data-toast-pos');
        toastr.success('Position: ' + pos.getAttribute('data-toast-pos').replace('toast-', ''));
      }
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
