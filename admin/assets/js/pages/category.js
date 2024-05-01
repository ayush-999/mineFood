$(document).ready(function () {
   var table = $('#category').DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: true,
   });

   $('.delete-category').click(function () {
      var categoryId = $(this).data('id');
      var categoryName = $(this)
         .closest('tr')
         .find('td:nth-child(2)')
         .text()
         .trim();
      Swal.fire({
         title: 'Are you sure you want to delete "' + categoryName + '"?',
         text: 'Type the category name to confirm.',
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
            if (inputValue !== categoryName) {
               Swal.showValidationMessage(
                  'You must type the exact category name to delete.'
               );
            }
            return inputValue === categoryName;
         },
         allowOutsideClick: () => !Swal.isLoading(),
      }).then((result) => {
         if (result.isConfirmed) {
            $.ajax({
               url: './ajax/delete_category.php',
               type: 'POST',
               data: { id: categoryId },
               success: function (response) {
                  Swal.fire(
                     'Deleted!',
                     'Your category has been deleted.',
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
                     'There was a problem deleting your category.',
                     'error'
                  );
               },
            });
         }
      });
   });

   $('#categoryForm').validate({
      rules: {
         categoryName: {
            required: true,
         },
         orderNumber: {
            required: true,
            digits: true,
         },
         categoryStatus: {
            required: true,
         },
      },
      messages: {
         categoryName: {
            required: 'Please enter category name',
         },
         orderNumber: {
            required: 'Please enter category order number',
         },
         categoryStatus: {
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
