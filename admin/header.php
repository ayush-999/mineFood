<?php
ob_start();
session_start();
include_once('config/database.php');
include_once('function.php');
include_once('classes/Admin.php');

if (!isset($_SESSION['IS_LOGIN'])) {
    redirect('login.php');
}

if (!empty($conn)) {
    $admin = new Admin($conn);
}

$currentScript = basename((string) $_SERVER['PHP_SELF']);

try {
    $adminDetails = json_decode((string) $admin->getAdminDetails(), true);
    $seoData = json_decode((string) $admin->getSeoSettingByPage($currentScript), true);
    $seoData = is_array($seoData) ? $seoData : [];
} catch (Exception $e) {
    error_log($e->getMessage());
    $seoData = [];
}

$profileImg = $adminDetails['admin_img'] ?? '';
$imagePath = $profileImg ? 'uploads/admin/profile-pic/' . $profileImg : 'assets/img/no-img.png';

try {
    $seoData = json_decode((string) $admin->getSeoSettingByPage($currentScript), true);
    $seoData = is_array($seoData) ? $seoData : [];
} catch (Exception $e) {
    error_log($e->getMessage());
    $seoData = [];
}

$pageTitle = $seoData['page_title'] ?? 'mine food';
$pageSubTitle = $seoData['sub_title'] ?? '';
$breadcrumbs = isset($seoData['breadcrumbs']) && is_array($seoData['breadcrumbs'])
    ? $seoData['breadcrumbs']
    : [];
$metaDescription = $seoData['meta_description'] ?? '';
$metaKeywords = $seoData['meta_keywords'] ?? '';
$canonicalUrl = $seoData['canonical_url'] ?? '';
$ogTitle = $seoData['og_title'] ?? $pageTitle;
$ogDescription = $seoData['og_description'] ?? $metaDescription;
$ogImage = $seoData['og_image'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars((string) $pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($metaKeywords); ?>">
    <?php if (!empty($canonicalUrl)): ?>
        <link rel="canonical" href="<?php echo htmlspecialchars((string) $canonicalUrl); ?>">
    <?php endif; ?>

    <!-- OpenGraph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($ogTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($ogDescription); ?>">
    <?php if (!empty($ogImage)): ?>
        <meta property="og:image" content="<?php echo htmlspecialchars((string) $ogImage); ?>">
    <?php endif; ?>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="<?php echo htmlspecialchars($ogTitle); ?>">
    <meta property="twitter:description" content="<?php echo htmlspecialchars($ogDescription); ?>">
    <?php if (!empty($ogImage)): ?>
        <meta property="twitter:image" content="<?php echo htmlspecialchars((string) $ogImage); ?>">
    <?php endif; ?>
    <meta property="twitter:site" content="@your_twitter_handle">
    <meta property="twitter:creator" content="@your_twitter_handle">

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

    <!-- jQuery -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <!-- Icon -->
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/plugins/simple-line/simple-line-icons.css">
    <link rel="stylesheet" href="assets/plugins/ionicons/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <!-- common style -->
    <link rel="stylesheet" href="assets/css/common.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/css/adminlte.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
    <script src="assets/plugins/toastr/toastr.min.js"></script>
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Img preview -->
    <script src="assets/plugins/imagePreview/imoViewer.js"></script>
    <!-- intlTelInput -->
    <link rel="stylesheet" href="assets/plugins/intlTelInput/css/intlTelInput.min.css">
    <script src="assets/plugins/intlTelInput/js/intlTelInputWithUtils.min.js"></script>
    <!-- CKEditor -->
    <script src="assets/plugins/ckeditor/ckeditor.js"></script>

    <link rel="stylesheet" href="assets/plugins/check-strength-password/asset/password-strength.css">
    <script src="assets/plugins/check-strength-password/asset/password-strength.js"></script>
    <!--========================= Pages JS =========================-->
    <script src="assets/js/common.js"></script>
    <script src="assets/js/address/address-dropdowns.js"></script>
    <script src="assets/js/pages/category.js"></script>
    <script src="assets/js/pages/user.js"></script>
    <script src="assets/js/pages/profile.js"></script>
    <script src="assets/js/pages/deliveryBoy.js"></script>
    <script src="assets/js/pages/couponCode.js"></script>
    <script src="assets/js/pages/dish.js"></script>
    <script src="assets/js/pages/banner.js"></script>
    <script src="assets/js/pages/setting.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="../user/index.php" class="btn btn-block btn-outline-secondary rounded-pill">Go to site</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <img src="<?php echo $imagePath; ?>" class="navUserImg" alt="User Image">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right pt-1 pb-1">
                        <a href="profile.php" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        <a href="logout.php" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include_once('side-bar.php'); ?>
        <!-- /.Main Sidebar Container -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <?php
                                if (!empty($breadcrumbs)) {
                                    $totalBreadcrumbs = count($breadcrumbs);
                                    foreach ($breadcrumbs as $index => $breadcrumb):
                                        // Ensure the breadcrumb has the required keys
                                        $breadcrumbTitle = $breadcrumb['title'] ?? '';
                                        $breadcrumbLink = $breadcrumb['link'] ?? '';
                                        $isActive = ($index == $totalBreadcrumbs - 1);
                                ?>
                                        <li class="breadcrumb-item <?php if ($isActive) echo 'active'; ?>" aria-current="page">
                                            <?php if (!$isActive && !empty($breadcrumbLink)): ?>
                                                <a href="<?php echo htmlspecialchars((string) $breadcrumbLink); ?>">
                                                    <?php echo htmlspecialchars((string) $breadcrumbTitle); ?>
                                                </a>
                                            <?php else: ?>
                                                <?php echo htmlspecialchars((string) $breadcrumbTitle); ?>
                                            <?php endif; ?>
                                        </li>
                                <?php endforeach;
                                }
                                ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">