<?php
include_once ('../header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $deliveryBoyId = intval($_POST['id']);
    $result = $admin->delete_deliveryBoy($deliveryBoyId);
    if ($result) {
        echo "Success";
    } else {
        echo "Error";
    }
}
?>