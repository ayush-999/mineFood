<?php
require_once('../config/database.php');
require_once('../classes/Admin.php');
require_once('../function.php');

header('Content-Type: application/json');
try {
    $admin = new Admin($conn);

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id <= 0) {
        throw new Exception('Invalid id');
    }

    $stmt = $conn->prepare("DELETE FROM setting WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Welcome message deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No record found to delete']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
