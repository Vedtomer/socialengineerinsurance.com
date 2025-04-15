<div class="modal fade" id="multiDeleteModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Delete Multiple Commissions</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="modal-text">Are you sure you want to delete the selected commission codes? This action cannot be undone.</p>
                <form id="bulkDeleteForm" action="{{ route('commission.bulk-delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ids" id="deleteIds">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light-dark" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" id="confirmBulkDelete" class="btn btn-danger">
                    <i class="fas fa-trash-alt me-1"></i> Delete All Selected
                </button>
            </div>
        </div>
    </div>
</div>