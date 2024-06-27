<?php
include_once('../header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $categoryId = intval($_POST['id']);
    $result = $admin->delete_category($categoryId);
    if ($result) {
        echo "Success";
    } else {
        echo "Error";
    }
}
?>