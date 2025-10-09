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

    // Debug logging
    error_log('POST data: ' . print_r($_POST, true));

    $data = [
        'welcome_message' => $_POST['welcome_message'] ?? ''
    ];

    // If id provided (edit flow), include it
    if (isset($_POST['id']) && $_POST['id'] !== '' && is_numeric($_POST['id'])) {
        $data['id'] = (int)$_POST['id'];
        error_log('Edit mode - ID: ' . $data['id']);
    } else {
        error_log('Add mode - No ID provided');
    }

    if ($_POST['action'] === 'save') {
        if (empty($data['welcome_message'])) {
            throw new Exception('Welcome message cannot be empty');
        }
        // If a settings row already exists and no id was provided, include its id
        // so saveWelcomeMessage will update the existing row instead of inserting a new one.
        if (empty($data['id'])) {
            $stmt = $conn->prepare("SELECT id FROM setting LIMIT 1");
            $stmt->execute();
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($existing && isset($existing['id'])) {
                $data['id'] = (int)$existing['id'];
            }
        }

        $result = $admin->saveWelcomeMessage($data);
    } else {
        throw new Exception('Invalid action');
    }

    echo $result;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}