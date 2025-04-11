<?php
ob_start();
session_start();
include_once('./admin/config/database.php');
include_once('./admin/function.php');
include_once('./admin/classes/User.php');
include_once('./user/default-setup.php');

if (!empty($currentScript)) {
    if (isset($pageSettings[$currentScript])) {
        $pageTitle = $pageSettings[$currentScript]['title'];
    } else {
        $pageTitle = "mine food";
    }
}

if (!empty($conn)) {
    $user = new User($conn);
}


?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo $pageTitle; ?></title>
    <link rel="shortcut icon" href="./user/assets/img/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./user/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./user/assets/css/animate.css">
    <link rel="stylesheet" href="./user/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./user/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="./user/assets/css/style.css">
    <link rel="stylesheet" href="./user/assets/css/custom.css">
    <link rel="stylesheet" href="./user/assets/css/responsive.css">
    <script src="./user/assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <div class="slider-area">
        <div class="slider-active owl-dot-style owl-carousel">
            <div class="single-slider pt-210 pb-220 bg-img" style="background-image:url(./user/assets/img/slider/slider-1.jpg);">
                <div class="container">
                    <div class="slider-content slider-animated-1">
                        <h1 class="animated">Drink & Heathy Food</h1>
                        <h3 class="animated">Fresh Heathy and Organic.</h3>
                        <div class="slider-btn mt-90">
                            <a class="animated" href="./user/home.php">Order Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-slider pt-210 pb-220 bg-img" style="background-image:url(./user/assets/img/slider/slider-2.jpg);">
                <div class="container">
                    <div class="slider-content slider-animated-1">
                        <h1 class="animated">Drink & Heathy Food</h1>
                        <h3 class="animated">Fresh Heathy and Organic.</h3>
                        <div class="slider-btn mt-90">
                            <a class="animated" href="./user/home.php">Order Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./user/assets/js/vendor/jquery-1.12.0.min.js"></script>
    <script src="./user/assets/js/popper.js"></script>
    <script src="./user/assets/js/bootstrap.min.js"></script>
    <script src="./user/assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="./user/assets/js/isotope.pkgd.min.js"></script>
    <script src="./user/assets/js/owl.carousel.min.js"></script>
    <script src="./user/assets/js/plugins.js"></script>
    <script src="./user/assets/js/main.js"></script>
</body>

</html>