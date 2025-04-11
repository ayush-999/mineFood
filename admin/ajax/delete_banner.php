<?php
include_once('../header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $bannerId = intval($_POST['id']);
    $result = $admin->delete_banner($bannerId);
    if ($result) {
        echo "Success";
    } else {
        echo "Error";
    }
}
?>