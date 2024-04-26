<?php
include_once ('../header.php');
include_once ('../classes/Admin.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $categoryId = intval($_POST['id']);
    $admin = new Admin($conn);
    $result = $admin->delete_category($categoryId);
    if ($result) {
        echo "Success";
    } else {
        echo "Error";
    }
}
?>