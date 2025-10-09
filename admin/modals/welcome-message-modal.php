<div class="modal fade" id="addWelcomeMessageModal" tabindex="-1" role="dialog" aria-labelledby="addWelcomeMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="addWelcomeMessageModalLabel">Add Welcome Message</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="welcomeMessageForm">
                    <input type="hidden" id="welcome_message_id" name="id" value="">
                    <div class="form-group">
                        <textarea class="form-control" id="welcome_message" name="welcome_message" rows="4" required></textarea>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-outline-secondary btn-block" data-dismiss="modal">Cancel</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn bg-gradient-success btn-block" id="saveWelcomeMessageBtn">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        // Save (add or update) welcome message
        $('#saveWelcomeMessageBtn').click(function () {
            const messageId = $('#welcome_message_id').val().trim();
            const welcomeMessage = $('#welcome_message').val();
            
            console.log('Saving - ID:', messageId, 'Message:', welcomeMessage); // Debug log
            
            const formData = {
                action: 'save',
                welcome_message: welcomeMessage
            };
            
            // Only add id if it's not empty
            if (messageId !== '') {
                formData.id = messageId;
            }
            
            console.log('Form data:', formData); // Debug log

            $.ajax({
                url: 'ajax/save-welcome-message.php',
                type: 'POST',
                data: formData,
                dataType: 'json'
            }).done(function (response) {
                console.log('Response:', response); // Debug log
                if (response.success) {
                    toastr.success(response.message);
                    $('#addWelcomeMessageModal').modal('hide');
                    $('#welcomeMessageForm')[0].reset();
                    setTimeout(() => location.reload(), 800);
                } else {
                    toastr.error(response.message);
                }
            }).fail(function () {
                toastr.error('An error occurred while saving the welcome message.');
            });
        });
        $('#addWelcomeMessageModal').on('hidden.bs.modal', function() {
            $('#welcomeMessageForm')[0].reset();
            $('#welcome_message_id').val('');
            $('#addWelcomeMessageModalLabel').text('Add Welcome Message');
            $('#saveWelcomeMessageBtn').text('Save');
        });

        // Open modal in add mode
        $(document).on('click', '#addWelcomeMessageBtn', function () {
            $('#addWelcomeMessageModalLabel').text('Add Welcome Message');
            $('#saveWelcomeMessageBtn').text('Save');
            $('#welcome_message_id').val('');
            $('#welcome_message').val('');
            $('#addWelcomeMessageModal').modal('show');
        });

        // Open modal in edit mode
        $(document).on('click', '#editWelcomeMessageBtn', function () {
            const id = $(this).attr('data-id') || '';
            let message = $(this).attr('data-message') || '';
            
            console.log('Edit clicked - ID:', id, 'Message:', message); // Debug log
            
            // Decode HTML entities if needed
            const textarea = document.createElement('textarea');
            textarea.innerHTML = message;
            message = textarea.value;
            
            $('#addWelcomeMessageModalLabel').text('Edit Welcome Message');
            $('#saveWelcomeMessageBtn').text('Update');
            $('#welcome_message_id').val(id);
            $('#welcome_message').val(message.replace(/<br\s*\/?>/gi, '\n'));
            $('#addWelcomeMessageModal').modal('show');
        });

        // Delete welcome message
        $(document).on('click', '#deleteWelcomeMessageBtn', function () {
            const id = $(this).data('id') || '';
            if (!id) {
                toastr.error('Invalid welcome message id.');
                return;
            }
            
            // Get the message from the welcome message container
            const message = $('#welcomeMessageContainer').text().trim() || 'this welcome message';

            // Show Swal prompt requiring typing DELETE to confirm
            Swal.fire({
                title: 'Are you sure you want to delete this welcome message?',
                html: '<div style="text-align:left; max-height:120px; overflow:auto; margin-bottom:8px;">' + $('<div>').text(message).html() + '</div>' +
                      'Type <strong>DELETE</strong> to confirm.',
                icon: 'warning',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                showLoaderOnConfirm: true,
                preConfirm: (inputValue) => {
                    if (inputValue !== 'DELETE') {
                        Swal.showValidationMessage('You must type DELETE to confirm.');
                    }
                    return inputValue === 'DELETE';
                },
                allowOutsideClick: () => !Swal.isLoading(),
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'ajax/delete-welcome-message.php',
                        type: 'POST',
                        data: { id: id },
                        dataType: 'json'
                    }).done(function (response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Failed!', response.message || 'Could not delete welcome message.', 'error');
                        }
                    }).fail(function () {
                        Swal.fire('Failed!', 'There was a problem deleting the welcome message.', 'error');
                    });
                }
            });
        });
    });
</script>