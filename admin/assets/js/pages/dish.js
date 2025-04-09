$(document).ready(function () {
    let table = $("#dish").DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: true,
    });

    $(".view-img").click(function () {
        $("#full-image").attr("src", $(this).attr("src"));
        $("#image-viewer").show();
    });

    $("#image-viewer .close").click(function () {
        $("#image-viewer").hide();
    });

    $(".delete-dish").click(function () {
        let dishId = $(this).data("id");
        let dishName = $(this).closest("tr").find("td:nth-child(2)").text().trim();
        Swal.fire({
            title: 'Are you sure you want to delete "' + dishName + '"?',
            text: "Type the dish name to confirm.",
            icon: "warning",
            input: "text",
            inputAttributes: {
                autocapitalize: "off",
            },
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            showLoaderOnConfirm: true,
            preConfirm: (inputValue) => {
                if (inputValue !== dishName) {
                    Swal.showValidationMessage(
                        "You must type the exact dish name to delete."
                    );
                }
                return inputValue === dishName;
            },
            allowOutsideClick: () => !Swal.isLoading(),
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "./ajax/delete_dish.php",
                    type: "POST",
                    data: {id: dishId},
                    success: function (response) {
                        Swal.fire(
                            "Deleted!",
                            "Your dish has been deleted.",
                            "success"
                        ).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); // Reload the page
                            }
                        });
                    },
                    error: function () {
                        Swal.fire(
                            "Failed!",
                            "There was a problem deleting your dish.",
                            "error"
                        );
                    },
                });
            }
        });
    });

    $("#dishForm").validate({
        rules: {
            dishName: {
                required: true,
            },
            dishStatus: {
                required: true,
            },
            dishCategory: {
                required: true,
            },
            dishType: {
                required: true,
            },
        },
        messages: {
            dishName: {
                required: "Please enter dish name",
            },
            dishStatus: {
                required: "Please select status",
            },
            dishCategory: {
                required: "Please select category",
            },
            dishType: {
                required: "Please select type",
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
