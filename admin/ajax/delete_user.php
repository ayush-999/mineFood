<?php
include_once ('../header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $userId = intval($_POST['id']);
    $result = $admin->delete_user($userId);
    if ($result) {
        echo "Success";
    } else {
        echo "Error";
    }
}
?>