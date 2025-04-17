<?php

$pageSettings = [
    'login.php' => [
        'title' => 'Login',
        'sub-title' => 'Login page',
        'breadcrumbs' => []
    ],
    'index.php' => [
        'title' => 'Home',
        'sub-title' => 'Dashboard page',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Dashboard', 'link' => 'index.php'],
        ]
    ],
    'category.php' => [
        'title' => 'Manage category',
        'sub-title' => 'Manage category',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Manage category', 'link' => 'category.php'],
        ]
    ],
    'user.php' => [
        'title' => 'Manage users',
        'sub-title' => 'Manage users',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Manage users', 'link' => 'user.php'],
        ]
    ],
    'settings.php' => [
        'title' => 'Manage setting',
        'sub-title' => 'Manage setting',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Manage setting', 'link' => 'settings.php'],
        ]
    ],
    'banner.php' => [
        'title' => 'Manage banner',
        'sub-title' => 'Manage banner',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Manage banner', 'link' => 'banner.php'],
        ]
    ],
    'profile.php' => [
        'title' => 'Manage profile',
        'sub-title' => 'Manage profile',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Manage profile', 'link' => 'profile.php'],
        ]
    ],
    'delivery-boy.php' => [
        'title' => 'Manage delivery boy',
        'sub-title' => 'Manage delivery boy',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Manage delivery boy', 'link' => 'delivery-boy.php'],
        ]
    ],
    'coupon-code.php' => [
        'title' => 'Manage coupon code',
        'sub-title' => 'Manage coupon code',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Manage coupon code', 'link' => 'coupon-code.php'],
        ]
    ],
    'dish.php' => [
        'title' => 'Manage dish',
        'sub-title' => 'Manage dish',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Manage dish', 'link' => 'dish.php'],
        ]
    ],
    'dishDetails.php' => [
        'title' => 'Manage dish',
        'sub-title' => 'Manage dish',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Manage dish', 'link' => 'dish.php'],
        ]
    ]
];

// Determine current script name
$currentScript = basename($_SERVER['PHP_SELF']);

?>