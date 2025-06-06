<?php
ob_start();
session_start();
include_once('../admin/config/database.php');
include_once('../admin/function.php');
include_once('../admin/classes/User.php');
include_once('./default-setup.php');

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

if (!empty($user)) {
    try {
        $getAdminDetails = json_decode((string) $user->getAdminDetailsForUser(), true);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="robots" content="noindex, follow" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?></title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/chosen.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/meanmenu.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <!-- header start -->
    <header class="header-area">
        <div class="header-top black-bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-12 col-sm-4">
                        <div class="welcome-area">
                            <p>Default welcome msg!</p>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-12 col-sm-8">
                        <div class="account-curr-lang-wrap f-right">
                            <ul>
                                <li class="top-hover">
                                    <a href="#">Language: (ENG) <i class="ion-chevron-down"></i></a>
                                    <ul>
                                        <li><a href="#">Bangla </a></li>
                                        <li><a href="#">Arabic</a></li>
                                        <li><a href="#">Hindi </a></li>
                                        <li><a href="#">Spanish</a></li>
                                    </ul>
                                </li>
                                <li class="top-hover">
                                    <a href="#">Currency: (USD) <i class="ion-chevron-down"></i></a>
                                    <ul>
                                        <li><a href="#">Taka (BDT)</a></li>
                                        <li><a href="#">Riyal (SAR)</a></li>
                                        <li><a href="#">Rupee (INR)</a></li>
                                        <li><a href="#">Dirham (AED)</a></li>
                                    </ul>
                                </li>
                                <li class="top-hover">
                                    <a href="#">Setting <i class="ion-chevron-down"></i></a>
                                    <ul>
                                        <li><a href="wishlist.html">Wishlist </a></li>
                                        <li><a href="login-register.html">Login</a></li>
                                        <li><a href="login-register.html">Register</a></li>
                                        <li><a href="my-account.html">my account</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-middle">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-12 col-sm-4">
                        <div class="logo">
                            <a href="index.php" class="d-flex align-items-center justify-content-lg-start justify-content-md-start justify-content-center">
                                <img alt="" src="assets/img/logo/logo-icon.png" class="main-logo" />
                                <h3 class="ml-1 mb-0"><b>mine</b>food.</h3>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-12 col-sm-8">
                        <div class="header-middle-right f-right">
                            <div class="header-login">
                                <a href="login-register.html">
                                    <div class="header-icon-style">
                                        <i class="icon-user icons"></i>
                                    </div>
                                    <div class="login-text-content">
                                        <p>
                                            Register <br />
                                            or <span>Sign in</span>
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <div class="header-wishlist">
                                <a href="wishlist.html">
                                    <div class="header-icon-style">
                                        <i class="icon-heart icons"></i>
                                    </div>
                                    <div class="wishlist-text">
                                        <p>
                                            Your <br />
                                            <span>Wishlist</span>
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <div class="header-cart">
                                <a href="#">
                                    <div class="header-icon-style">
                                        <i class="icon-handbag icons"></i>
                                        <span class="count-style">02</span>
                                    </div>
                                    <div class="cart-text">
                                        <span class="digit">My Cart</span>
                                        <span class="cart-digit-bold">$209.00</span>
                                    </div>
                                </a>
                                <div class="shopping-cart-content">
                                    <ul>
                                        <li class="single-shopping-cart">
                                            <div class="shopping-cart-img">
                                                <a href="#"><img alt="" src="assets/img/cart/cart-1.jpg" /></a>
                                            </div>
                                            <div class="shopping-cart-title">
                                                <h4><a href="#">Phantom Remote </a></h4>
                                                <h6>Qty: 02</h6>
                                                <span>$260.00</span>
                                            </div>
                                            <div class="shopping-cart-delete">
                                                <a href="#"><i class="ion ion-close"></i></a>
                                            </div>
                                        </li>
                                        <li class="single-shopping-cart">
                                            <div class="shopping-cart-img">
                                                <a href="#"><img alt="" src="assets/img/cart/cart-2.jpg" /></a>
                                            </div>
                                            <div class="shopping-cart-title">
                                                <h4><a href="#">Phantom Remote</a></h4>
                                                <h6>Qty: 02</h6>
                                                <span>$260.00</span>
                                            </div>
                                            <div class="shopping-cart-delete">
                                                <a href="#"><i class="ion ion-close"></i></a>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="shopping-cart-total">
                                        <h4>Shipping : <span>$20.00</span></h4>
                                        <h4>Total : <span class="shop-total">$260.00</span></h4>
                                    </div>
                                    <div class="shopping-cart-btn">
                                        <a href="cart-page.html">view cart</a>
                                        <a href="checkout.html">checkout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom transparent-bar black-bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="main-menu">
                            <nav>
                                <ul>
                                    <li><a href="index.php">Home</a></li>
                                    <!-- <li class="top-hover">
                                        <a href="index.html">home <i class="ion-chevron-down"></i></a>
                                        <ul class="submenu">
                                            <li><a href="index.html">home version 1</a></li>
                                            <li><a href="index-2.html">home version 2</a></li>
                                        </ul>
                                    </li> -->
                                    <li><a href="about-us.html">about</a></li>
                                    <li class="mega-menu-position top-hover">
                                        <a href="shop.html">shop <i class="ion-chevron-down"></i></a>
                                        <ul class="mega-menu">
                                            <li>
                                                <ul>
                                                    <li class="mega-menu-title">
                                                        <a href="#">Categories 01</a>
                                                    </li>
                                                    <li><a href="shop.html">salad</a></li>
                                                    <li><a href="shop.html">sandwich</a></li>
                                                    <li><a href="shop.html">bread</a></li>
                                                    <li><a href="shop.html">steak</a></li>
                                                    <li><a href="shop.html">tuna steak</a></li>
                                                    <li><a href="shop.html">spaghetti </a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <ul>
                                                    <li class="mega-menu-title">
                                                        <a href="#">Categories 02</a>
                                                    </li>
                                                    <li><a href="shop.html">rice</a></li>
                                                    <li><a href="shop.html">pizza</a></li>
                                                    <li><a href="shop.html">hamburger</a></li>
                                                    <li><a href="shop.html">eggs</a></li>
                                                    <li><a href="shop.html">sausages</a></li>
                                                    <li><a href="shop.html">apple juice</a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <ul>
                                                    <li class="mega-menu-title">
                                                        <a href="#">Categories 03</a>
                                                    </li>
                                                    <li><a href="shop.html">milk</a></li>
                                                    <li><a href="shop.html">grape juice</a></li>
                                                    <li><a href="shop.html">cookie</a></li>
                                                    <li><a href="shop.html">candy</a></li>
                                                    <li><a href="shop.html">cake</a></li>
                                                    <li><a href="shop.html">cupcake</a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <ul>
                                                    <li class="mega-menu-title">
                                                        <a href="#">Categories 04</a>
                                                    </li>
                                                    <li><a href="shop.html">pie</a></li>
                                                    <li><a href="shop.html">stoberry</a></li>
                                                    <li><a href="shop.html">sandwich</a></li>
                                                    <li><a href="shop.html">bread</a></li>
                                                    <li><a href="shop.html">steak</a></li>
                                                    <li><a href="shop.html">hamburger</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    </li>
                                    <li><a href="contact.html">contact us</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- mobile-menu-area-start -->
        <div class="mobile-menu-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mobile-menu">
                            <nav id="mobile-menu-active">
                                <ul class="menu-overflow" id="nav">
                                    <li>
                                        <a href="index.html">Home</a>
                                        <ul>
                                            <li><a href="index.html">home version 1</a></li>
                                            <li><a href="index-2.html">home version 2</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#">pages</a>
                                        <ul>
                                            <li><a href="about-us.html">about us </a></li>
                                            <li><a href="shop.html">shop Grid</a></li>
                                            <li><a href="shop-list.html">shop list</a></li>
                                            <li>
                                                <a href="product-details.html">product details</a>
                                            </li>
                                            <li><a href="cart-page.html">cart page</a></li>
                                            <li><a href="checkout.html">checkout</a></li>
                                            <li><a href="wishlist.html">wishlist</a></li>
                                            <li><a href="my-account.html">my account</a></li>
                                            <li>
                                                <a href="login-register.html">login / register</a>
                                            </li>
                                            <li><a href="contact.html">contact</a></li>
                                            <li><a href="testimonial.html">Testimonials</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="shop.html"> Shop </a>
                                        <ul>
                                            <li>
                                                <a href="#">Categories 01</a>
                                                <ul>
                                                    <li><a href="shop.html">salad</a></li>
                                                    <li><a href="shop.html">sandwich</a></li>
                                                    <li><a href="shop.html">bread</a></li>
                                                    <li><a href="shop.html">steak</a></li>
                                                    <li><a href="shop.html">tuna steak</a></li>
                                                    <li><a href="shop.html">spaghetti </a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#">Categories 02</a>
                                                <ul>
                                                    <li><a href="shop.html">rice</a></li>
                                                    <li><a href="shop.html">pizza</a></li>
                                                    <li><a href="shop.html">hamburger</a></li>
                                                    <li><a href="shop.html">eggs</a></li>
                                                    <li><a href="shop.html">sausages</a></li>
                                                    <li><a href="shop.html">apple juice</a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#">Categories 03</a>
                                                <ul>
                                                    <li><a href="shop.html">milk</a></li>
                                                    <li><a href="shop.html">grape juice</a></li>
                                                    <li><a href="shop.html">cookie</a></li>
                                                    <li><a href="shop.html">candy</a></li>
                                                    <li><a href="shop.html">cake</a></li>
                                                    <li><a href="shop.html">cupcake</a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#">Categories 04</a>
                                                <ul>
                                                    <li><a href="shop.html">pie</a></li>
                                                    <li><a href="shop.html">stoberry</a></li>
                                                    <li><a href="shop.html">sandwich</a></li>
                                                    <li><a href="shop.html">bread</a></li>
                                                    <li><a href="shop.html">steak</a></li>
                                                    <li><a href="shop.html">hamburger</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="blog-rightsidebar.html">blog</a>
                                        <ul>
                                            <li><a href="blog.html">Blog No sidebar</a></li>
                                            <li>
                                                <a href="blog-rightsidebar.html">Blog sidebar</a>
                                            </li>
                                            <li><a href="blog-details.html">Blog details</a></li>
                                            <li>
                                                <a href="blog-details-gallery.html">Blog details gallery</a>
                                            </li>
                                            <li>
                                                <a href="blog-details-video.html">Blog details video</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a href="contact.html">contact us</a></li>
                                    <li><a href="shop.html">burger</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- mobile-menu-area-end -->
    </header>