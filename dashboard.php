<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

require_once 'functions.php';

// Pilih database yang akan digunakan
$active_db = $_GET['db'] ?? 'mysql';
setActiveDB($active_db);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Project Komter Kelompok 5-B</a>
            <div class="navbar-nav ms-auto">
                <span class="nav-link">Welcome, <?= $_SESSION['username'] ?></span>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label"><strong>Pilih Database:</strong></label>
                    <form method="GET" id="dbForm">
                        <select name="db" class="form-select" onchange="document.getElementById('dbForm').submit()">
                            <option value="mysql" <?= $active_db=='mysql' ? 'selected' : '' ?>>MySQL</option>
                            <option value="pgsql" <?= $active_db=='pgsql' ? 'selected' : '' ?>>PostgreSQL</option>
                            <option value="sqlsrv" <?= $active_db=='sqlsrv' ? 'selected' : '' ?>>SQL Server</option>
                        </select>
                    </form>
                </div>
                <hr>
                <div class="list-group">
                    <a href="mahasiswa/index.php?db=<?= $active_db ?>" class="list-group-item list-group-item-action">Data Mahasiswa</a>
                    <a href="dosen/index.php?db=<?= $active_db ?>" class="list-group-item list-group-item-action">Data Dosen</a>
                    <a href="matakuliah/index.php?db=<?= $active_db ?>" class="list-group-item list-group-item-action">Data Matakuliah</a>
                    <a href="kuliah/index.php?db=<?= $active_db ?>" class="list-group-item list-group-item-action">Data Kuliah</a>
                </div>
            </div>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5>Selamat Datang di Sistem Akademik</h5>
                    </div>
                    <div class="card-body">
                        <h5>Database Aktif: <?= strtoupper($active_db) ?></h5>
                        <p>Silakan pilih menu di samping untuk mengelola data.</p>
                        <div class="alert alert-info">
                            <strong>Info:</strong> Sistem terhubung ke 3 database berbeda (MySQL, PostgreSQL, SQL Server) Iziin~ 🫷 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
