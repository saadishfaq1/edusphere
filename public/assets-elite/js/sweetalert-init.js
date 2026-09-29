/* ==========================================================================
   ElitePanel — SweetAlert2 Advanced Examples
   ========================================================================== */
(function () {
  function init() {
    if (typeof Swal === 'undefined') return;

    var P = '#14C38E';
    var DARK = '#0F172A';

    // Branded button style
    var defaults = {
      confirmButtonColor: P,
      cancelButtonColor: '#94A3B8',
      buttonsStyling: true,
      customClass: { popup: 'rt-swal' }
    };

    // Toast preset
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: function (toast) {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
      },
      customClass: { popup: 'rt-swal-toast' }
    });

    document.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-swal]');
      if (!btn) return;
      var t = btn.getAttribute('data-swal');

      /* ============== STANDARD ICONS ============== */
      if (t === 'basic') Swal.fire(Object.assign({}, defaults, { title: 'Hello!', text: 'This is a basic alert.' }));
      else if (t === 'success') Swal.fire(Object.assign({}, defaults, { icon: 'success', title: 'Saved!', text: 'Your changes have been saved.' }));
      else if (t === 'info') Swal.fire(Object.assign({}, defaults, { icon: 'info', title: 'Heads up', text: 'Just letting you know.' }));
      else if (t === 'warning') Swal.fire(Object.assign({}, defaults, { icon: 'warning', title: 'Warning', text: 'This may have side effects.' }));
      else if (t === 'error') Swal.fire(Object.assign({}, defaults, { icon: 'error', title: 'Oops!', text: 'Something went wrong.' }));
      else if (t === 'question') Swal.fire(Object.assign({}, defaults, { icon: 'question', title: 'Are you sure?' }));

      /* ============== POSITIONS ============== */
      else if (t === 'pos-tl') Swal.fire(Object.assign({}, defaults, { icon: 'success', title: 'Top-Left', position: 'top-start', showConfirmButton: false, timer: 1800 }));
      else if (t === 'pos-tc') Swal.fire(Object.assign({}, defaults, { icon: 'success', title: 'Top', position: 'top', showConfirmButton: false, timer: 1800 }));
      else if (t === 'pos-tr') Swal.fire(Object.assign({}, defaults, { icon: 'success', title: 'Top-Right', position: 'top-end', showConfirmButton: false, timer: 1800 }));
      else if (t === 'pos-c') Swal.fire(Object.assign({}, defaults, { icon: 'success', title: 'Center', position: 'center', showConfirmButton: false, timer: 1800 }));
      else if (t === 'pos-bl') Swal.fire(Object.assign({}, defaults, { icon: 'success', title: 'Bottom-Left', position: 'bottom-start', showConfirmButton: false, timer: 1800 }));
      else if (t === 'pos-br') Swal.fire(Object.assign({}, defaults, { icon: 'success', title: 'Bottom-Right', position: 'bottom-end', showConfirmButton: false, timer: 1800 }));

      /* ============== TIMER ============== */
      else if (t === 'timer-3s') Swal.fire(Object.assign({}, defaults, { icon: 'info', title: 'Auto-closes in 3s', timer: 3000, showConfirmButton: false }));
      else if (t === 'timer-progress') Swal.fire(Object.assign({}, defaults, {
        icon: 'success', title: 'Saved!', html: 'Closing in <b></b> seconds.',
        timer: 5000, timerProgressBar: true, showConfirmButton: false,
        didOpen: function () {
          Swal.showLoading();
          var timer = Swal.getPopup().querySelector('b');
          var i = setInterval(function () {
            if (Swal.getTimerLeft()) timer.textContent = Math.ceil(Swal.getTimerLeft() / 1000);
          }, 100);
          Swal.getPopup().addEventListener('hidden.bs.modal', function () { clearInterval(i); });
        }
      }));
      else if (t === 'no-backdrop') Swal.fire(Object.assign({}, defaults, { icon: 'info', title: 'No backdrop', text: 'Click outside to dismiss.', backdrop: false }));
      else if (t === 'non-dismissible') Swal.fire(Object.assign({}, defaults, { icon: 'success', title: 'Locked dialog', text: 'You can only confirm.', allowOutsideClick: false, allowEscapeKey: false }));

      /* ============== CONFIRMATIONS ============== */
      else if (t === 'confirm') Swal.fire(Object.assign({}, defaults, {
        icon: 'warning', title: 'Delete this property?', text: "This action cannot be undone.",
        showCancelButton: true, confirmButtonColor: '#EF4444', confirmButtonText: 'Yes, delete it'
      })).then(function (r) { if (r.isConfirmed) Swal.fire(Object.assign({}, defaults, { icon: 'success', title: 'Deleted!', text: 'The property has been removed.' })); });

      else if (t === 'reason') Swal.fire(Object.assign({}, defaults, {
        title: 'Reason for cancellation',
        input: 'textarea',
        inputPlaceholder: 'Tell us why you are cancelling...',
        inputValidator: function (v) { if (!v) return 'Reason is required'; },
        showCancelButton: true, confirmButtonText: 'Submit',
        confirmButtonColor: '#EF4444'
      }));

      else if (t === '3-buttons') Swal.fire(Object.assign({}, defaults, {
        title: 'Save changes?',
        text: 'You have unsaved edits.',
        icon: 'question',
        showDenyButton: true, showCancelButton: true,
        confirmButtonText: 'Save', denyButtonText: 'Discard', cancelButtonText: 'Cancel',
        denyButtonColor: '#F59E0B'
      })).then(function (r) {
        if (r.isConfirmed) Toast.fire({ icon: 'success', title: 'Changes saved' });
        else if (r.isDenied) Toast.fire({ icon: 'warning', title: 'Changes discarded' });
      });

      else if (t === 'async-confirm') Swal.fire(Object.assign({}, defaults, {
        title: 'Process this transaction?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Process',
        showLoaderOnConfirm: true,
        preConfirm: function () { return new Promise(function (resolve) { setTimeout(resolve, 1500); }); },
        allowOutsideClick: function () { return !Swal.isLoading(); }
      })).then(function (r) {
        if (r.isConfirmed) Swal.fire(Object.assign({}, defaults, { icon: 'success', title: 'Processed!', text: 'Transaction completed in 1.5s.' }));
      });

      /* ============== INPUT TYPES ============== */
      else if (t === 'input-text') Swal.fire(Object.assign({}, defaults, { title: 'Enter property name', input: 'text', inputPlaceholder: 'e.g. Hillside Villa', showCancelButton: true })).then(function (r) { if (r.value) Toast.fire({ icon: 'success', title: 'Got: ' + r.value }); });
      else if (t === 'input-email') Swal.fire(Object.assign({}, defaults, { title: 'Enter your email', input: 'email', inputPlaceholder: 'you@example.com', showCancelButton: true, validationMessage: 'Please enter a valid email' }));
      else if (t === 'input-password') Swal.fire(Object.assign({}, defaults, { title: 'Enter password', input: 'password', inputAttributes: { autocapitalize: 'off', autocorrect: 'off' }, showCancelButton: true }));
      else if (t === 'input-select') Swal.fire(Object.assign({}, defaults, {
        title: 'Property type', input: 'select',
        inputOptions: { house: 'House', villa: 'Villa', apartment: 'Apartment', studio: 'Studio', commercial: 'Commercial' },
        inputPlaceholder: 'Select type', showCancelButton: true
      })).then(function (r) { if (r.value) Toast.fire({ icon: 'success', title: 'Selected: ' + r.value }); });
      else if (t === 'input-radio') Swal.fire(Object.assign({}, defaults, {
        title: 'Listing status', input: 'radio',
        inputOptions: { active: 'Active', pending: 'Pending', sold: 'Sold' }, inputValue: 'active',
        showCancelButton: true
      }));
      else if (t === 'input-checkbox') Swal.fire(Object.assign({}, defaults, {
        title: 'Terms &amp; conditions', input: 'checkbox',
        inputValue: 0, inputPlaceholder: 'I agree to the ElitePanel terms',
        confirmButtonText: 'Continue <i class="bi bi-arrow-right"></i>',
        inputValidator: function (r) { return !r && 'You must agree first :)'; }
      }));
      else if (t === 'input-textarea') Swal.fire(Object.assign({}, defaults, {
        title: 'Property description', input: 'textarea',
        inputPlaceholder: 'Write a compelling description...',
        inputAttributes: { 'aria-label': 'Description' }, showCancelButton: true
      }));
      else if (t === 'input-range') Swal.fire(Object.assign({}, defaults, {
        title: 'Set max price ($K)', input: 'range', inputAttributes: { min: 50, max: 5000, step: 50 }, inputValue: 800
      }));
      else if (t === 'input-file') Swal.fire(Object.assign({}, defaults, {
        title: 'Upload property photo', input: 'file',
        inputAttributes: { accept: 'image/*', 'aria-label': 'Upload photo' }
      })).then(function (r) {
        if (r.value) {
          var reader = new FileReader();
          reader.onload = function (e) { Swal.fire(Object.assign({}, defaults, { title: 'Uploaded', imageUrl: e.target.result, imageAlt: 'Photo' })); };
          reader.readAsDataURL(r.value);
        }
      });
      else if (t === 'input-date') Swal.fire(Object.assign({}, defaults, { title: 'Select showing date', input: 'date', showCancelButton: true }));

      /* ============== TOASTS ============== */
      else if (t === 'toast-success') Toast.fire({ icon: 'success', title: 'Listing published successfully' });
      else if (t === 'toast-info') Toast.fire({ icon: 'info', title: '3 new leads imported' });
      else if (t === 'toast-warning') Toast.fire({ icon: 'warning', title: 'Listing expires in 7 days' });
      else if (t === 'toast-error') Toast.fire({ icon: 'error', title: 'Could not save changes' });
      else if (t === 'toast-mixin') Toast.fire({ icon: 'info', title: 'Update available — <a href="#" class="text-primary fw-bold">View</a>', html: 'Update available &mdash; <a href="#" class="text-primary fw-bold">View</a>' });

      /* ============== ANIMATIONS ============== */
      else if (t === 'anim-bounce') Swal.fire(Object.assign({}, defaults, { icon: 'success', title: 'Bounce!', showClass: { popup: 'animate__animated animate__bounceIn' }, hideClass: { popup: 'animate__animated animate__bounceOut' } }));
      else if (t === 'anim-zoom') Swal.fire(Object.assign({}, defaults, { icon: 'info', title: 'Zoom!', showClass: { popup: 'animate__animated animate__zoomIn' }, hideClass: { popup: 'animate__animated animate__zoomOut' } }));
      else if (t === 'anim-slide-down') Swal.fire(Object.assign({}, defaults, { icon: 'info', title: 'Slide Down', showClass: { popup: 'animate__animated animate__fadeInDown' }, hideClass: { popup: 'animate__animated animate__fadeOutUp' } }));
      else if (t === 'anim-flip') Swal.fire(Object.assign({}, defaults, { icon: 'success', title: 'Flip!', showClass: { popup: 'animate__animated animate__flipInX' }, hideClass: { popup: 'animate__animated animate__flipOutX' } }));
      else if (t === 'anim-shake') Swal.fire(Object.assign({}, defaults, { icon: 'error', title: 'Shake!', showClass: { popup: 'animate__animated animate__headShake' }, hideClass: { popup: 'animate__animated animate__fadeOut' } }));

      /* ============== RICH HTML / IMAGES ============== */
      else if (t === 'html') Swal.fire(Object.assign({}, defaults, {
        title: '<strong>Hot Listing</strong>',
        html: 'A new property matched your <b>luxury</b> filter:<br><br><img src="../../assets/images/unsplash/thumbnail.jpg" class="rounded mb-2"><br><strong class="text-primary">$1,250,000</strong>'
      }));
      else if (t === 'image') Swal.fire(Object.assign({}, defaults, {
        title: 'Hillside Premium Estate',
        text: '$680,000 — 1,890 ft²',
        imageUrl: '../../assets/images/unsplash/rounded-house.jpg',
        imageWidth: 400, imageAlt: 'Property'
      }));
      else if (t === 'image-bg') Swal.fire(Object.assign({}, defaults, {
        title: '<span style="color:#fff">Stunning View</span>',
        html: '<span style="color:#fff">Beachfront luxury starting at <strong>$2.4M</strong></span>',
        background: 'url(../../assets/images/unsplash/suburban.jpg) center/cover',
        confirmButtonText: 'View Listings',
        showCancelButton: true, cancelButtonText: 'Later'
      }));
      else if (t === 'iframe') Swal.fire(Object.assign({}, defaults, {
        title: 'Property Tour Video',
        html: '<div class="ratio ratio-16x9"><iframe src="https://www.youtube.com/embed/EngW7tLk6R8" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>',
        width: 600
      }));
      else if (t === 'property-card') Swal.fire(Object.assign({}, defaults, {
        title: false,
        html: '<div class="text-start"><img src="../../assets/images/unsplash/rounded-house.jpg" class="w-100 rounded mb-3"><span class="badge bg-primary mb-2">For Sale</span><h5 class="mb-1">Hillside Premium Estate</h5><p class="text-muted small mb-2"><i class="bi bi-geo-alt"></i> 2904 Spring Avenue, Boston</p><div class="d-flex gap-3 small text-muted mb-2"><span><i class="bi bi-arrows-fullscreen text-primary"></i> 1,890 ft²</span><span><i class="bi bi-door-closed text-primary"></i> 4 Bed</span><span><i class="bi bi-droplet text-primary"></i> 3 Bath</span></div><div class="text-primary fw-bold fs-4 mb-2">$680,000</div></div>',
        showCloseButton: true,
        confirmButtonText: 'View Full Details',
        showCancelButton: true, cancelButtonText: 'Close',
        width: 500
      }));

      /* ============== STEP/QUEUE WIZARDS ============== */
      else if (t === 'wizard-onboard') {
        var Step = Swal.mixin(Object.assign({}, defaults, {
          confirmButtonText: 'Next <i class="bi bi-arrow-right"></i>',
          showCancelButton: true, cancelButtonText: 'Cancel',
          progressSteps: ['1','2','3'],
          allowOutsideClick: false
        }));
        Step.fire({ title: 'Welcome to ElitePanel!', text: 'Let’s set up your account in 3 quick steps.', currentProgressStep: 0, icon: 'info' })
          .then(function (r) { if (r.isConfirmed) return Step.fire({ title: 'Add your first listing', text: 'Upload property details and photos.', currentProgressStep: 1, icon: 'info' }); })
          .then(function (r) { if (r && r.isConfirmed) return Step.fire({ title: 'Invite your team', text: 'Add agents to collaborate.', currentProgressStep: 2, icon: 'info', confirmButtonText: 'Finish' }); })
          .then(function (r) { if (r && r.isConfirmed) Toast.fire({ icon: 'success', title: 'You’re all set! 🎉' }); });
      }

      else if (t === 'wizard-listing') {
        var Step = Swal.mixin(Object.assign({}, defaults, {
          showCancelButton: true, allowOutsideClick: false,
          progressSteps: ['1','2','3'], confirmButtonText: 'Next <i class="bi bi-arrow-right"></i>'
        }));
        Step.fire({ title: 'Property Details', input: 'text', inputPlaceholder: 'Property title', currentProgressStep: 0 })
          .then(function (r) { if (r.isConfirmed) return Step.fire({ title: 'Pricing', input: 'number', inputPlaceholder: 'Price (USD)', currentProgressStep: 1 }); })
          .then(function (r) { if (r && r.isConfirmed) return Step.fire({ title: 'Status', input: 'select', inputOptions: { active: 'Active', draft: 'Draft' }, currentProgressStep: 2, confirmButtonText: 'Publish' }); })
          .then(function (r) { if (r && r.isConfirmed) Toast.fire({ icon: 'success', title: 'Listing published successfully!' }); });
      }

      else if (t === 'queue-multi') {
        Swal.mixin(Object.assign({}, defaults, { progressSteps: ['1','2','3','4'], confirmButtonText: 'Next' }))
          .queue([
            { title: 'Step 1: Personal info', input: 'text', inputPlaceholder: 'Your name' },
            { title: 'Step 2: Email', input: 'email', inputPlaceholder: 'you@example.com' },
            { title: 'Step 3: Choose plan', input: 'radio', inputOptions: { starter: 'Starter', pro: 'Pro', business: 'Business' }, inputValue: 'pro' },
            { title: 'Step 4: Review', html: '<p>Confirm to finish setup.</p>', confirmButtonText: 'Finish' }
          ]).then(function (r) {
            if (r.value) Swal.fire(Object.assign({}, defaults, { icon: 'success', title: 'All Done!', html: 'Your responses:<br><pre class="bg-body rounded p-2 small text-start">' + JSON.stringify(r.value, null, 2) + '</pre>' }));
          });
      }

      /* ============== REAL ESTATE USE CASES ============== */
      else if (t === 'listing-published') Swal.fire(Object.assign({}, defaults, {
        icon: 'success',
        title: 'Listing Published! 🎉',
        html: '<strong>Hillside Premium Estate</strong> is now live.<br><a href="#" class="text-primary fw-semibold">View on ElitePanel →</a>',
        timer: 3500, timerProgressBar: true
      }));

      else if (t === 'lead-archived') Swal.fire(Object.assign({}, defaults, {
        icon: 'info', title: 'Lead archived',
        text: 'You can restore it anytime from the Archived tab.',
        showCancelButton: true, cancelButtonText: 'Undo',
        confirmButtonText: 'OK'
      })).then(function (r) {
        if (r.dismiss === Swal.DismissReason.cancel) Toast.fire({ icon: 'success', title: 'Lead restored' });
      });

      else if (t === 'delete-property') Swal.fire(Object.assign({}, defaults, {
        title: 'Delete this property?',
        html: 'Type <strong>DELETE</strong> to confirm permanent deletion of <strong>Hillside Premium Estate</strong>.',
        input: 'text', inputPlaceholder: 'Type DELETE',
        showCancelButton: true,
        confirmButtonText: 'Delete', confirmButtonColor: '#EF4444',
        inputValidator: function (v) { if (v !== 'DELETE') return 'Please type DELETE to confirm'; }
      })).then(function (r) { if (r.isConfirmed) Toast.fire({ icon: 'success', title: 'Property deleted' }); });

      else if (t === 'deal-closed') Swal.fire(Object.assign({}, defaults, {
        title: '🎉 Deal Closed!',
        html: '<p>Commission of <strong class="text-primary">$20,400</strong> added to your account.</p>',
        icon: 'success',
        showConfirmButton: true, confirmButtonText: 'Awesome!'
      }));

      else if (t === 'schedule-showing') Swal.fire(Object.assign({}, defaults, {
        title: 'Schedule a Showing',
        html: '<div class="text-start"><label class="form-label fs-13">Date</label><input id="sw-date" type="date" class="swal2-input m-0 mb-2"><label class="form-label fs-13">Time</label><input id="sw-time" type="time" class="swal2-input m-0 mb-2"><label class="form-label fs-13">Notes</label><textarea id="sw-notes" class="swal2-textarea m-0" placeholder="Special requests..."></textarea></div>',
        showCancelButton: true, confirmButtonText: 'Schedule',
        preConfirm: function () {
          var date = document.getElementById('sw-date').value;
          var time = document.getElementById('sw-time').value;
          if (!date || !time) Swal.showValidationMessage('Please pick date and time');
          return { date: date, time: time };
        }
      })).then(function (r) { if (r.isConfirmed) Toast.fire({ icon: 'success', title: 'Showing scheduled for ' + r.value.date + ' ' + r.value.time }); });

      else if (t === 'invite-agent') Swal.fire(Object.assign({}, defaults, {
        title: 'Invite an Agent',
        html: '<input id="iv-email" type="email" class="swal2-input" placeholder="agent@email.com"><select id="iv-role" class="swal2-select"><option>Agent</option><option>Manager</option><option>Admin</option></select>',
        confirmButtonText: '<i class="bi bi-send"></i> Send Invite',
        showCancelButton: true,
        preConfirm: function () {
          return { email: document.getElementById('iv-email').value, role: document.getElementById('iv-role').value };
        }
      })).then(function (r) { if (r.isConfirmed && r.value.email) Toast.fire({ icon: 'success', title: 'Invite sent to ' + r.value.email }); });

      else if (t === 'contact-buyer') Swal.fire(Object.assign({}, defaults, {
        title: 'Contact Tapasya Datta',
        html: '<div class="d-flex align-items-center gap-2 mb-3"><img src="../../assets/images/unsplash/sophia-bennett.jpg" class="rounded-circle" width="48" height="48"><div class="text-start"><strong class="d-block">Tapasya Datta</strong><small class="text-muted">tapasya@example.com</small></div></div><textarea id="cb-msg" class="swal2-textarea m-0" placeholder="Type your message..." style="min-height:100px"></textarea>',
        showCancelButton: true, confirmButtonText: '<i class="bi bi-send"></i> Send',
        width: 460
      }));

      else if (t === 'payment-success') Swal.fire(Object.assign({}, defaults, {
        title: 'Payment Successful',
        html: '<div class="text-success fs-1 mb-2"><i class="bi bi-check-circle-fill"></i></div><p>Your <strong>$129.00</strong> Pro plan subscription is active.<br><small class="text-muted">Receipt #INV-2941 emailed to sophia@elitepanel.com</small></p>',
        showCancelButton: true, cancelButtonText: 'Close',
        confirmButtonText: 'View Receipt'
      }));
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
