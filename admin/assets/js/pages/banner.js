$(document).ready(function () {
  var table = $("#banner").DataTable({
    responsive: true,
    lengthChange: false,
    autoWidth: true,
  });

  $(".delete-banner").click(function () {
    var bannerId = $(this).data("id");

    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!",
      cancelButtonText: "No",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "./ajax/delete_banner.php",
          type: "POST",
          data: { id: bannerId },
          success: function (response) {
            Swal.fire(
              "Deleted!",
              "The banner has been deleted.",
              "success"
            ).then(() => {
              location.reload();
            });
          },
          error: function () {
            Swal.fire(
              "Failed!",
              "There was a problem deleting the banner.",
              "error"
            );
          },
        });
      }
    });
  });

  $("#bannerForm").validate({
    rules: {
      bannerHeading: {
        required: true,
      },
      bannerSubHeading: {
        required: true,
      },
      bannerLink: {
        required: true,
      },
      bannerLinkText: {
        required: true,
      },
      bannerStatus: {
        required: true,
      },
      bannerOrderNumber: {
        required: true,
        digits: true,
      },
    },
    messages: {
      bannerHeading: {
        required: "Please enter heading",
      },
      bannerSubHeading: {
        required: "Please enter sub heading",
      },
      bannerLink: {
        required: "Please enter link",
      },
      bannerLinkText: {
        required: "Please enter link name",
      },
      bannerStatus: {
        required: "Please select status",
      },
      bannerOrderNumber: {
        required: "Please enter order number",
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
