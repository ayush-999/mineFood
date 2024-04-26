<?php
include_once ('../header.php');
include_once ('../classes/Admin.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch the data from the POST request
    $categoryName = isset($_POST['categoryName']) ? trim($_POST['categoryName']) : null;
    $orderNumber = isset($_POST['orderNumber']) ? intval($_POST['orderNumber']) : null;
    $status = isset($_POST['categoryStatus']) ? $_POST['categoryStatus'] : '0';  // Default status is '0'

    // Create a new instance of the Admin class
    $admin = new Admin($conn);

    // Attempt to add the category
    $result = $admin->add_category($categoryName, $orderNumber, $status);

    if ($result === "Category added successfully") {
        echo json_encode(array("success" => true, "message" => $result));
    } else {
        echo json_encode(array("success" => false, "message" => $result));
    }
} else {
    // Not a POST request
    header('HTTP/1.1 405 Method Not Allowed');
    echo "Request method not supported.";
}
?>