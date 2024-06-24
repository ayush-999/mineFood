<?php
session_start();
include_once ('config/database.php');
include_once ('function.php');
include_once ('default-setup.php');

include_once ('classes/Admin.php');

if (isset($_SESSION['IS_LOGIN'])) {
    redirect('index.php');
}

if (isset($pageSettings[$currentScript])) {
    $pageTitle = $pageSettings[$currentScript]['title'];
    $pageSubTitle = $pageSettings[$currentScript]['sub-title'];
    $breadcrumbs = $pageSettings[$currentScript]['breadcrumbs'];
} else {
    $pageTitle = "mine food";
    $breadcrumbs = [];
}

$user = new Admin($conn);

$msg = "";
$inputErrorClass = "";
$iconErrorClass = "";
$textErrorClass = "";
$cardErrorClass = "";

if (isset($_POST['submit'])) {
    $username = $_POST['userInput'];
    // $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $password = $_POST['password'];
    $result = $user->admin_login($username, $password);
    if ($result) {
        $_SESSION['IS_LOGIN'] = 'yes';
        redirect('index.php');
    } else {
        $msg = "Invalid login credentials! Please try again.";
        $inputErrorClass = "input-error";
        $iconErrorClass = "icon-error";
        $textErrorClass = "font-error";
        $cardErrorClass = "card-outline-error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login to access your personal dashboard and manage your preferences.">
    <meta name="keywords" content="login, user login, secure login, dashboard access">
    <meta name="author" content="Ayush">
    <meta name="robots" content="index, follow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $pageTitle; ?></title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/css/adminlte.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
    <!-- common style -->
    <link rel="stylesheet" href="assets/css/common.css">
    <script src="assets/js/common.js"></script>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary login-card <?php echo $cardErrorClass; ?>">
            <div class="card-header text-center">
                <a href="" class="h1 <?php echo $textErrorClass; ?>"><b>mine</b>food.</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg <?php echo $textErrorClass; ?>">Sign in to start your session</p>

                <form action="" method="post" id="loginForm">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control <?php echo $inputErrorClass; ?>" name="userInput"
                            placeholder="Enter username" required>
                        <div class="input-group-append">
                            <div class="input-group-text <?php echo $inputErrorClass; ?>">
                                <span class="fas fa-envelope fs-14 <?php echo $iconErrorClass; ?>"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control <?php echo $inputErrorClass; ?>" name="password"
                            id="password-field" placeholder="Enter password" required>
                        <div class="input-group-append">
                            <div class="input-group-text <?php echo $inputErrorClass; ?>">
                                <span id="password-icon" class="fas fa-lock <?php echo $iconErrorClass; ?>"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-2">
                            <input type="submit" class="btn bg-gradient-primary btn-block normal-btn" id="signIn-button"
                                name="submit" value="Sign In" disabled>
                        </div>
                    </div>
                </form>
                <p class="mb-2">
                    <a href="/" class="<?php echo $textErrorClass; ?>">I forgot my password</a>
                </p>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script src="assets/plugins/jquery-validation/jquery.validate.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="assets/plugins/bootstrap/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="assets/js/adminlte.min.js"></script>
    <!-- Toastr -->
    <script src="assets/plugins/toastr/toastr.min.js"></script>
    <!--========================= Pages JS =========================-->
    <script src="assets/js/pages/login.js"></script>
    <script type="text/javascript">
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

        $('#loginForm').validate({
            rules: {
                password: {
                    required: true,
                    minlength: 5
                },
            },
            messages: {
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.input-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    })
    </script>
</body>

</html>