{{-- Global confirmation modal for forms with data-bc-confirm — see resources/js/app.js --}}
<div class="modal fade" id="bcConfirmModal" tabindex="-1" aria-labelledby="bcConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bc-modal border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold" id="bcConfirmModalLabel">Confirm action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="mb-0 text-secondary small" id="bcConfirmMessage">Are you sure?</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-bc-primary btn-sm rounded-pill px-3" id="bcConfirmOk">Confirm</button>
            </div>
        </div>
    </div>
</div>
