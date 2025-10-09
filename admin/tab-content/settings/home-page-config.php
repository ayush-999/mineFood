<?php
$getWelcomeMsg = [];
try {
    $getWelcomeMsg = json_decode((string) $admin->getSetting(), true) ?: [];
} catch (Exception $e) {
    error_log($e->getMessage());
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="text-bold mb-0">Welcome Message</h6>
            <?php if (!empty($getWelcomeMsg) && isset($getWelcomeMsg[0]['welcome_message']) && $getWelcomeMsg[0]['welcome_message'] !== ''): ?>
                <?php $wm = $getWelcomeMsg[0]; ?>
                <div class="btn-group" role="group" aria-label="Welcome message actions">
                    <button type="button" id="editWelcomeMessageBtn" class="btn btn-sm btn-outline-primary rounded-pill mr-2" data-id="<?php echo htmlspecialchars($wm['id'] ?? '', ENT_QUOTES); ?>" data-message="<?php echo htmlspecialchars($wm['welcome_message'] ?? '', ENT_QUOTES); ?>">
                        <i class="fa-regular fa-pen-to-square"></i> Edit
                    </button>
                    <button type="button" id="deleteWelcomeMessageBtn" class="btn btn-sm btn-outline-danger rounded-pill" data-id="<?php echo htmlspecialchars($wm['id'] ?? '', ENT_QUOTES); ?>">
                        <i class="fa-regular fa-trash"></i> Delete
                    </button>
                </div>
            <?php else: ?>
                <button type="button" id="addWelcomeMessageBtn" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="fas fa-plus"></i> Add
                </button>
            <?php endif; ?>
        </div>
        <div class="welcomeMessageContainer mt-3" id="welcomeMessageContainer">
            <?php $admin->renderWelcomeMessage($getWelcomeMsg); ?>
        </div>
    </div>
</div>