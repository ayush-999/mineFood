<?php
require_once('../config/database.php');
require_once('../classes/Admin.php');
require_once('../function.php');

header('Content-Type: application/json');

try {
    $admin = new Admin($conn);
    
    $data = [
        'id' => $_POST['id'] ?? null,
        'title' => $_POST['title'] ?? '',
        'url' => $_POST['url'] ?? '',
        'icon' => $_POST['icon'] ?? ''
    ];
    
    if ($_POST['action'] === 'save') {
        $result = $admin->saveSocialMedia($data);
    } elseif ($_POST['action'] === 'delete') {
        if (empty($data['id'])) {
            throw new Exception('ID is required for deletion');
        }
        $result = $admin->deleteSocialMedia($data['id']);
    } else {
        throw new Exception('Invalid action');
    }

    echo $result;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}