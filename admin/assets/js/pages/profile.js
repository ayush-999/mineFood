$(document).ready(function () {
  $("#profileForm").validate({
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
      mobile: {
        required: true,
        maxlength: 15,
        minlength: 10,
      },
    },
    messages: {
      fullName: {
        required: "Please enter full name",
      },
      email: {
        required: "Please enter email address",
      },
      username: {
        required: "Please enter username",
      },
      mobile: {
        required: "Please enter mobile number",
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

  $("#changePasswordForm").validate({
    rules: {
      oldPassword: {
        required: true,
        minlength: 6,
      },
      newPassword: {
        required: true,
        minlength: 6,
      },
      confirmPassword: {
        equalTo: "#newPassword",
      },
    },
    messages: {
      oldPassword: {
        required: "Please enter password",
      },
      newPassword: {
        required: "Please enter password",
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
