<?php
include_once('../header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $couponCodeId = intval($_POST['id']);
    $result = $admin->delete_couponCode($couponCodeId);
    if ($result) {
        echo "Success";
    } else {
        echo "Error";
    }
}
?>