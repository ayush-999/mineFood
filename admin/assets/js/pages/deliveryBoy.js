$(document).ready(function () {
    var table = $('#deliveryBoy').DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: true,
    });

    $('.delete-deliveryBoy').click(function () {
        var deliveryBoyId = $(this).data('id');
        var deliveryBoy = $(this)
            .closest('tr')
            .find('td:nth-child(2)')
            .text()
            .trim();
        Swal.fire({
            title: 'Are you sure you want to delete "' + deliveryBoy + '"?',
            text: 'Type the delivery boy name to confirm.',
            icon: 'warning',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off',
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,
            preConfirm: (inputValue) => {
                if (inputValue !== deliveryBoy) {
                    Swal.showValidationMessage(
                        'You must type the exact delivery boy name to delete.'
                    );
                }
                return inputValue === deliveryBoy;
            },
            allowOutsideClick: () => !Swal.isLoading(),
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: './ajax/delete_deliveryBoy.php',
                    type: 'POST',
                    data: {id: deliveryBoyId},
                    success: function (response) {
                        Swal.fire(
                            'Deleted!',
                            'Your delivery boy has been deleted.',
                            'success'
                        ).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); // Reload the page
                            }
                        });
                    },
                    error: function () {
                        Swal.fire(
                            'Failed!',
                            'There was a problem deleting your delivery boy.',
                            'error'
                        );
                    },
                });
            }
        });
    });

    $('#deliveryBoyForm').validate({
        rules: {
            deliveryBoyName: {
                required: true,
            },
            deliveryBoyMobile: {
                required: true,
                maxlength: 15,
                minlength: 10,
            },
            deliveryBoyEmail: {
                required: true,
                email: true,
            },
            deliveryBoyStatus: {
                required: true,
            },
        },
        messages: {
            deliveryBoyName: {
                required: 'Please enter full name',
            },
            deliveryBoyMobile: {
                required: 'Please enter mobile number',
            },
            deliveryBoyEmail: {
                required: 'Please enter email',
            },
            deliveryBoyStatus: {
                required: 'Please select status',
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});
