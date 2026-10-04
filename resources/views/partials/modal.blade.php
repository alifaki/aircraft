<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-white">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirm-delete-progress"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm " data-bs-dismiss="modal">
                    <span class="ti ti-x"></span>
                    Cancel
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="confirm-delete-btn">
                    <span class="ti ti-trash d-btn me-1"></span> Delete
                </button>
            </div>
        </div>
    </div>
</div>
