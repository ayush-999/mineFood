<?php
require_once('../config/database.php');
require_once('../classes/Admin.php');
require_once('../function.php');

header('Content-Type: application/json');

try {
    $admin = new Admin($conn);

    // Get admin details to get the admin ID
    $adminDetails = json_decode((string) $admin->getAdminDetails(), true);
    if (!$adminDetails || !isset($adminDetails['id'])) {
        throw new Exception('Could not retrieve admin information');
    }
    $adminId = (int)$adminDetails['id'];

    $data = [
        'id' => $_POST['id'] ?? null,
        'title' => $_POST['title'] ?? '',
        'url' => $_POST['url'] ?? '',
        'icon' => $_POST['icon'] ?? ''
    ];

    if ($_POST['action'] === 'save') {
        $result = $admin->saveSocialMedia($data, $adminId);
    } elseif ($_POST['action'] === 'delete') {
        if (empty($data['id'])) {
            throw new Exception('ID is required for deletion');
        }
        $result = $admin->deleteSocialMedia((int)$data['id'], $adminId);
    } else {
        throw new Exception('Invalid action');
    }

    echo $result;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
