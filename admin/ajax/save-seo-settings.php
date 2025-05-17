<?php
require_once('../config/database.php');
require_once('../classes/Admin.php');
require_once('../function.php');

header('Content-Type: application/json');

try {
    $admin = new Admin($conn);
    
    $data = [
        'id' => $_POST['id'] ?? null,
        'page_name' => $_POST['page_name'],
        'page_title' => $_POST['page_title'] ?? '',
        'meta_description' => $_POST['meta_description'] ?? '',
        'meta_keywords' => $_POST['meta_keywords'] ?? '',
        'canonical_url' => $_POST['canonical_url'] ?? '',
        'og_title' => $_POST['og_title'] ?? '',
        'og_description' => $_POST['og_description'] ?? '',
        'og_image' => $_POST['og_image'] ?? '',
        'sub_title' => $_POST['sub_title'] ?? '',
        'breadcrumbs' => isset($_POST['breadcrumbs']) ? json_decode((string) $_POST['breadcrumbs'], true) : []
    ];
    
    $result = $admin->saveSeoSettings($data);
    echo $result;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}