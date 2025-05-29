<div class="modal fade" id="addSocialModal" tabindex="-1" role="dialog" aria-labelledby="addSocialModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSocialModalLabel">Add New Social Media Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addSocialForm">
                    <div class="form-group">
                        <label for="socialTitle">Title</label>
                        <input type="text" class="form-control" id="socialTitle" name="title" required>
                        <small class="form-text text-muted">e.g. Facebook, Twitter, Instagram</small>
                    </div>
                    <div class="form-group">
                        <label for="socialUrl">URL</label>
                        <input type="url" class="form-control" id="socialUrl" name="url" required placeholder="https://">
                        <small class="form-text text-muted">Full URL to your social media profile</small>
                    </div>
                    <div class="form-group">
                        <label for="socialIcon">Icon Class</label>
                        <input type="text" class="form-control" id="socialIcon" name="icon" required>
                        <small class="form-text text-muted">Font Awesome icon class (e.g. "fa-brands fa-facebook-f")</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveSocialBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle form submission from modal
    $('#saveSocialBtn').click(function() {
        const formData = {
            action: 'save',
            title: $('#socialTitle').val(),
            url: $('#socialUrl').val(),
            icon: $('#socialIcon').val()
        };

        $.ajax({
            url: 'ajax/save-social-media.php',
            type: 'POST',
            data: formData,
            dataType: 'json'
        }).done(function(response) {
            if (response.success) {
                toastr.success(response.message);
                $('#addSocialModal').modal('hide');
                $('#addSocialForm')[0].reset();
                // Refresh the social media list
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

    // Reset form when modal is closed
    $('#addSocialModal').on('hidden.bs.modal', function () {
        $('#addSocialForm')[0].reset();
    });
});
</script>