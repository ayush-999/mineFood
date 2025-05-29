<?php
$socialMedia = [];
try {
    $socialMedia = json_decode((string) $admin->getSocialMedia(), true) ?: [];
} catch (Exception $e) {
    error_log($e->getMessage());
}
?>

<div class="row align-items-center">
    <div class="col-md-12">
        <div class="d-flex justify-content-end">
            <button class="btn btn-outline-info btn-sm rounded-pill" type="button" id="addSocialBtn">
                <i class="fas fa-plus"></i> Add Link
            </button>
        </div>
    </div>
</div>
<form id="socialMediaForm" class="mt-4">
    <div class="row">
        <div class="col-md-12">
            <?php foreach ($socialMedia as $social): ?>
                <div class="row align-items-center mb-3 social-media-row" data-id="<?= $social['id'] ?>">
                    <div class="col-sm-2">
                        <div class="title-wrapper">
                            <h6 class="mb-0"><?= htmlspecialchars($social['title']) ?></h6>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="icon-wrapper">
                            <i class="<?= htmlspecialchars($social['icon']) ?>"></i>
                        </div>
                    </div>
                    <div class="col-sm-8 text-secondary">
                        <div class="link-wrapper">
                            <div class="input-group">
                                <input type="url" class="form-control social-url"
                                    value="<?= htmlspecialchars($social['url']) ?>"
                                    data-original="<?= htmlspecialchars($social['url']) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-danger btn-sm delete-social"
                            data-id="<?= $social['id'] ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <hr>
            <?php endforeach; ?>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Handle delete
        $(document).on('click', '.delete-social', function() {
            const id = $(this).data('id');
            if (!confirm('Are you sure you want to delete this social media link?')) return;

            $.ajax({
                url: 'ajax/save-social-media.php',
                type: 'POST',
                data: {
                    action: 'delete',
                    id: id
                },
                dataType: 'json',
            }).done(function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    // Remove the row
                    $('.social-media-row[data-id="' + id + '"]').remove();
                } else {
                    toastr.error(response.message);
                }
            }).fail(function(xhr, status, error) {
                toastr.error('An error occurred: ' + error);
            });
        });
    });
</script>