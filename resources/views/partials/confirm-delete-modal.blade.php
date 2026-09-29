<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="icon-tile bg-danger-soft text-danger mx-auto mb-3">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h2 class="h5" id="confirmDeleteTitle">Delete this item?</h2>
                <p class="text-muted small mb-4" id="confirmDeleteMessage">This action cannot be undone.</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">Cancel</button>
                    <form id="confirmDeleteForm" method="POST" class="w-100">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(url, title, message) {
        document.getElementById('confirmDeleteForm').action = url;
        document.getElementById('confirmDeleteTitle').textContent = title || 'Delete this item?';
        document.getElementById('confirmDeleteMessage').textContent = message || 'This action cannot be undone.';

        var modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        modal.show();
    }
</script>