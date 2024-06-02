$(document).ready(function () {
   $('#profileForm').validate({
      rules: {
         fullName: {
            required: true,
         },
         email: {
            required: true,
            email: true,
         },
         username: {
            required: true,
         },
         password: {
            required: true,
            minlength: 6,
         },
         confirm_password: {
            equalTo: '#password',
         },
         mobile: {
            required: true,
            maxlength: 15,
         },
         address: {
            required: true,
         },
      },
      messages: {
         fullName: {
            required: 'Please enter full name',
         },
         email: {
            required: 'Please enter email address',
         },
         username: {
            required: 'Please enter username',
         },
         password: {
            required: 'Please enter password',
         },
         mobile: {
            required: 'Please enter mobile number',
         },
         address: {
            required: 'Please enter your address',
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
