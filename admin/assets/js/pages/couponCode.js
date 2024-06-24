$(document).ready(function () {
   var table = $('#couponCode').DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: true,
   });

   $('.delete-couponCode').click(function () {
      var couponCodeId = $(this).data('id');
      var couponCode = $(this)
         .closest('tr')
         .find('td:nth-child(2)')
         .text()
         .trim();
      Swal.fire({
         title: 'Are you sure you want to delete "' + couponCode + '"?',
         text: 'Type the coupon code name to confirm.',
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
            if (inputValue !== couponCode) {
               Swal.showValidationMessage(
                  'You must type the exact coupon code name to delete.'
               );
            }
            return inputValue === couponCode;
         },
         allowOutsideClick: () => !Swal.isLoading(),
      }).then((result) => {
         if (result.isConfirmed) {
            $.ajax({
               url: './ajax/delete_couponCode.php',
               type: 'POST',
               data: { id: couponCodeId },
               success: function (response) {
                  Swal.fire(
                     'Deleted!',
                     'Your coupon code has been deleted.',
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
                     'There was a problem deleting your coupon code.',
                     'error'
                  );
               },
            });
         }
      });
   });

   $('#couponCodeForm').validate({
      rules: {
         couponCodeName: {
            required: true,
            maxlength: 10,
            minlength: 5,
         },
         couponCodeStatus: {
            required: true,
         },
      },
      messages: {
         couponCodeName: {
            required: 'Please coupon name',
         },
         couponCodeStatus: {
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
