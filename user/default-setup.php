<?php

$pageSettings = [
    'index.php' => [
        'title' => 'Welcome to mineFood'
    ],
    'home.php' => [
        'title' => 'Home',
        'sub-title' => 'Home page',
        'breadcrumbs' => [
            ['title' => 'Home', 'link' => 'home.php'],
        ]
    ],
];

// Determine current script name
$currentScript = basename((string) $_SERVER['PHP_SELF']);
