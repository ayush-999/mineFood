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
    'site-settings.php' => [
        'title' => 'Site Setting',
        'sub-title' => 'Site Setting',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'index.php'],
            ['title' => 'Site Setting', 'link' => 'site-settings.php'],
        ]
    ]
];

// Determine current script name
$currentScript = basename($_SERVER['PHP_SELF']);

?>