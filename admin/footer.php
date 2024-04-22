<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/js/adminlte.min.js"></script>
<!-- Toastr -->
<script src="assets/plugins/toastr/toastr.min.js"></script>
<!--  -->
<script src="assets/js/main.js"></script>
<script>
$(document).ready(function() {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "100", // default : 300
        "hideDuration": "500", // default : 1000
        "timeOut": "2000", // default : 5000
        "extendedTimeOut": "500", // default : 1000
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "onHidden": function() {
            removeErrorClasses();
        }
    };

    function removeErrorClasses() {
        $('.input-error').removeClass('input-error');
        $('.icon-error').removeClass('icon-error');
        $('.font-error').removeClass('font-error');
        $('.card-outline-error').removeClass('card-outline-error');
        $('#signIn-button').removeClass('bg-gradient-danger').addClass('normal-btn');
        $('.login-card').removeClass('shake-animation');
    }

    function applyErrorStyles() {
        $('#signIn-button').removeClass('normal-btn').addClass('bg-gradient-danger');
    }

    var errorMessage = <?php echo json_encode($msg); ?>;
    if (errorMessage) {
        toastr.error(errorMessage);
        applyErrorStyles();
        $('.login-card').addClass('shake-animation');
    }

    // Function to check the input fields
    function checkInputs() {
        var userInput = $('input[name="userInput"]').val().trim();
        var password = $('input[name="password"]').val().trim();

        if (userInput !== '' && password !== '') {
            $('#signIn-button').prop('disabled', false);
        } else {
            $('#signIn-button').prop('disabled', true);
        }
    }

    // Call checkInputs on input change
    $('input[name="userInput"], input[name="password"]').on('keyup', checkInputs);
})
</script>
</body>

</html>