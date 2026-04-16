<?php
session_start();

// Always redirect to login first
header('Location: login.php');
exit();
?>
