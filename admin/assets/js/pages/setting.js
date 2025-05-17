$(document).ready(function () {
  $("#contactDetailsForm").validate({
    rules: {
      contactEmail: {
        required: true,
        email: true,
      },
      contactNumber: {
        required: true,
        maxlength: 15,
        minlength: 10,
      },
    },
    messages: {
      contactEmail: {
        required: "Please enter contact email address",
      },
      contactNumber: {
        required: "Please enter contact mobile number",
      },
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid");
    },
  });
});
