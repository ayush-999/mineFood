$(document).ready(function () {
  var table = $("#slider").DataTable({
    responsive: true,
    lengthChange: false,
    autoWidth: true,
  });
 
  $(".delete-slider").click(function () {
    var sliderId = $(this).data("id");

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
          url: "./ajax/delete_slider.php",
          type: "POST",
          data: { id: sliderId },
          success: function (response) {
            Swal.fire(
              "Deleted!",
              "The slider has been deleted.",
              "success"
            ).then(() => {
              location.reload();
            });
          },
          error: function () {
            Swal.fire(
              "Failed!",
              "There was a problem deleting the slider.",
              "error"
            );
          },
        });
      }
    });
  });

  $("#sliderForm").validate({
    rules: {
      sliderHeading: {
        required: true,
      },
      sliderSubHeading: {
        required: true,
      },
      sliderLink: {
        required: true,
      },
      sliderLinkText: {
        required: true,
      },
      sliderStatus: {
        required: true,
      },
      sliderOrderNumber: {
        required: true,
        digits: true,
      },
    },
    messages: {
      sliderHeading: {
        required: "Please enter heading",
      },
      sliderSubHeading: {
        required: "Please enter sub heading",
      },
      sliderLink: {
        required: "Please enter link",
      },
      sliderLinkText: {
        required: "Please enter link name",
      },
      sliderStatus: {
        required: "Please select status",
      },
      sliderOrderNumber: {
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
