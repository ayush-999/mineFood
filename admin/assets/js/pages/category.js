$(document).ready(function () {
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

   $('#addCategoryForm').submit(function (event) {
      event.preventDefault();
      var categoryName = $('#categoryName').val();
      var orderNumber = $('#orderNumber').val();
      var status = $('#categoryStatus').val();

      console.log(categoryName, orderNumber, status);
      // Perform client-side validation
      if (categoryName && orderNumber && status) {
         $.ajax({
            url: './ajax/add_category.php',
            type: 'POST',
            data: {
               categoryName: categoryName,
               orderNumber: orderNumber,
               categoryStatus: status,
            },
            success: function (response) {
               Swal.fire(
                  'Success!',
                  'Category added successfully',
                  'success'
               ).then((result) => {
                  if (result.isConfirmed) {
                     $('#category-modal').modal('hide');
                     location.reload(); // Reload to update the category list
                  }
               });
            },
            error: function () {
               Swal.fire('Failed!', 'Unable to add category.', 'error');
            },
         });
      } else {
         // Alert the user that all fields are required
         Swal.fire(
            'Warning!',
            'Please fill all the fields before submitting.',
            'warning'
         );
      }
   });
});
