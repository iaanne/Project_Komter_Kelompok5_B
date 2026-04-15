<?php
session_start();

// Redirect ke login jika belum login
if(!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Jika sudah login, redirect ke dashboard
header('Location: dashboard.php');
exit();
?>
