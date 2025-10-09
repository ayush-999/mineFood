<?php
$socialMedia = [];
try {
    $socialMedia = json_decode((string) $admin->getSocialMedia(), true) ?: [];
} catch (Exception $e) {
    error_log($e->getMessage());
}
?>
<!-- TODO need to work on this page bugs -->
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
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="p-2">Title</th>
                <th class="p-2">Icon</th>
                <th class="p-2">URL</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($socialMedia as $social): ?>
                <tr class="social-media-row" data-id="<?= $social['id'] ?>">
                    <td class="align-middle p-2">
                        <h6 class="mb-0"><?= htmlspecialchars($social['title']) ?></h6>
                    </td>
                    <td class="align-middle text-center">
                        <i class="<?= htmlspecialchars($social['icon']) ?> fs-24"></i>
                    </td>
                    <td class="align-middle text-center p-2">
                        <input type="url" class="form-control social-url"
                            value="<?= htmlspecialchars($social['url']) ?>"
                            data-original="<?= htmlspecialchars($social['url']) ?>" disabled>
                    </td>
                    <td class="align-middle text-center p-2">
                        <button type="button" class="btn bg-gradient-danger btn-sm rounded-circle delete-social"
                            data-id="<?= $social['id'] ?>">
                            <i class="fa-regular fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</form>

<script>
    $(document).ready(function() {
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
                    $('.social-media-row[data-id="' + id + '"]').remove();
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message);
                }
            }).fail(function(xhr, status, error) {
                toastr.error('An error occurred: ' + error);
            });
        });
    });
</script>