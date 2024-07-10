<?php
include_once('../header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $dishId = intval($_POST['id']);
    $result = $admin->delete_dish($dishId);
    if ($result) {
        echo "Success";
    } else {
        echo "Error";
    }
}
?>