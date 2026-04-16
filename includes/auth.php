<?php
require_once __DIR__ . '/../config/users.php';

function authenticateUser($username, $password) {
    global $users;
    if (isset($users[$username]) && password_verify($password, $users[$username])) {
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;
        return true;
    }
    return false;
}

function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit();
    }
}
?>
