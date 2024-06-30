$(document).ready(function () {
   var table = $('#user').DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: true,
   });

   $('.delete-user').click(function () {
      var userId = $(this).data('id');
      var userName = $(this)
         .closest('tr')
         .find('td:nth-child(2)')
         .text()
         .trim();
      Swal.fire({
         title: 'Are you sure you want to delete "' + userName + '"?',
         text: 'Type the user name to confirm.',
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
            if (inputValue !== userName) {
               Swal.showValidationMessage(
                  'You must type the exact user name to delete.'
               );
            }
            return inputValue === userName;
         },
         allowOutsideClick: () => !Swal.isLoading(),
      }).then((result) => {
         if (result.isConfirmed) {
            $.ajax({
               url: './ajax/delete_user.php',
               type: 'POST',
               data: { id: userId },
               success: function (response) {
                  Swal.fire(
                     'Deleted!',
                     'Your user has been deleted.',
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
                     'There was a problem deleting your user.',
                     'error'
                  );
               },
            });
         }
      });
   });

   $('#userForm').validate({
      rules: {
         userName: {
            required: true,
         },
         userMobile: {
            required: true,
            maxlength: 15,
            minlength: 12,
         },
         userEmail: {
            required: true,
            email: true,
         },
         userStatus: {
            required: true,
         },
      },
      messages: {
         userName: {
            required: 'Please enter full name',
         },
         userMobile: {
            required: 'Please enter mobile number',
         },
         userEmail: {
            required: 'Please enter email',
         },
         userStatus: {
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
