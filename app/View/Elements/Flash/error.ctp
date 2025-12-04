<div class="flash-message flash-error" id="flashMessage">
    <div class="flash-icon">
        <i class="bi bi-x-circle-fill fs-5"></i>
    </div>
    <div class="flash-content">
        <strong>Erro</strong>
        <span><?php echo h($message); ?></span>
    </div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" onclick="this.parentElement.remove();"></button>
</div>
