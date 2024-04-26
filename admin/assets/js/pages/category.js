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
});
