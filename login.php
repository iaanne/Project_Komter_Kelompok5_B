<?php
session_start();

require_once 'includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (!$username || !$password) {
        $error = 'Username dan password harus diisi!';
    } elseif (authenticateUser($username, $password)) {
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Login Sistem Akademik</h4>
                    </div>
                    <div class="card-body">
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form method="POST" autocomplete="off">
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" id="username" class="form-control" autocomplete="off" spellcheck="false" required>
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" id="password" class="form-control" autocomplete="new-password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Clear autofilled values on page load
        window.addEventListener('load', function() {
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
        });
    </script>
</body>
</html>
