<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php" class="brand-link">
        <img src="assets/img/logo-icon.png" alt="mine food" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light h4"><b>mine</b>food.</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                <img src="<?php if (!empty($imagePath)) {
                    echo $imagePath;
                } ?>" class="img-circle sidebar-img" alt="User Image">
            </div>
            <div class="info">
                <a href="profile.php"
                   class="d-block <?= basename((string) $_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">
                    <?php if (!empty($adminDetails)) {
                        echo $adminDetails['name'];
                    } ?>
                </a>
                <small class="text-secondary"><?php echo $adminDetails['email']; ?></small>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="index.php"
                       class="nav-link <?= basename((string) $_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="banner.php"
                       class="nav-link <?= basename((string) $_SERVER['PHP_SELF']) == 'banner.php' ? 'active' : '' ?>">
                        <i class="nav-icon fa-solid fa-images"></i>
                        <p>
                            Banner
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="category.php"
                       class="nav-link <?= basename((string) $_SERVER['PHP_SELF']) == 'category.php' ? 'active' : '' ?>">
                        <i class="nav-icon fa-solid fa-layer-group"></i>
                        <p>
                            Category
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="user.php"
                       class="nav-link <?= basename((string) $_SERVER['PHP_SELF']) == 'user.php' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="delivery-boy.php"
                       class="nav-link <?= basename((string) $_SERVER['PHP_SELF']) == 'delivery-boy.php' ? 'active' : '' ?>">
                        <i class="nav-icon fa-solid fa-person-carry-box"></i>
                        <p>
                            Delivery Boy
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="coupon-code.php"
                       class="nav-link <?= basename((string) $_SERVER['PHP_SELF']) == 'coupon-code.php' ? 'active' : '' ?>">
                        <i class="nav-icon fa-solid fa-badge-percent"></i>
                        <p>
                            Coupon Code
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="dish.php"
                       class="nav-link <?= (basename((string) $_SERVER['PHP_SELF']) == 'dish.php') || (basename((string) $_SERVER['PHP_SELF']) == 'dishDetails.php') ? 'active' : ''?>">
                        <i class="nav-icon fa-solid fa-utensils"></i>
                        <p>
                            Dish
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="settings.php"
                       class="nav-link <?= basename((string) $_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Site Settings
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>