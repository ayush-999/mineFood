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
        'title' => 'Category',
        'sub-title' => 'Category Master',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Category', 'link' => 'category.php'],
        ]
    ],
    'user.php' => [
        'title' => 'Users',
        'sub-title' => 'Users Master',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Users', 'link' => 'user.php'],
        ]
    ],
    'site-settings.php' => [
        'title' => 'Site Setting',
        'sub-title' => 'Site Setting',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Site Setting', 'link' => 'site-settings.php'],
        ]
    ],
    'profile.php' => [
        'title' => 'Profile',
        'sub-title' => 'Profile Setting',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Profile Setting', 'link' => 'profile.php'],
        ]
    ],
    'delivery-boy.php' => [
        'title' => 'Delivery Boy',
        'sub-title' => 'Delivery Boy',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Delivery Boy', 'link' => 'delivery-boy.php'],
        ]
    ],
    'coupon-code.php' => [
        'title' => 'Coupon Code',
        'sub-title' => 'Coupon Code',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Coupon Code', 'link' => 'coupon-code.php'],
        ]
    ]
];

// Determine current script name
$currentScript = basename($_SERVER['PHP_SELF']);

?>