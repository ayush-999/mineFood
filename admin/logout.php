<?php
session_start();
include_once ('function.php');
unset($_SESSION['IS_LOGIN']);
redirect('login.php');
?>