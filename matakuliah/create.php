<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit();
}

$active_db = $_GET['db'] ?? 'mysql';

switch($active_db) {
    case 'pgsql':
        require_once '../config/pgsql.php';
        $pdo = $pdo_pgsql;
        break;
    case 'sqlsrv':
        require_once '../config/sqlsrv.php';
        $pdo = $pdo_sqlsrv;
        break;
    default:
        require_once '../config/mysql.php';
        $pdo = $pdo_mysql;
}

$message = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kodeMK = isset($_POST['kodeMK']) ? $_POST['kodeMK'] : '';
    $namaMK = isset($_POST['namaMK']) ? $_POST['namaMK'] : '';
    $sks = isset($_POST['sks']) ? $_POST['sks'] : '';
    $smt = isset($_POST['smt']) ? $_POST['smt'] : '';
    
    if(!$kodeMK || !$namaMK || !$sks || !$smt) {
        $error = "Semua field harus diisi!";
    } else {
    
    try {
        $sql = "INSERT INTO matkul (kodeMK, namaMK, sks, smt) VALUES (:kodeMK, :namaMK, :sks, :smt)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':kodeMK' => $kodeMK,
            ':namaMK' => $namaMK,
            ':sks' => $sks,
            ':smt' => $smt
        ]);
        $message = "Data matakuliah berhasil ditambahkan!";
        header("refresh:2;url=index.php?db=$active_db");
    } catch(PDOException $e) {
        $error = "Gagal menambah data: " . $e->getMessage();
    }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Matakuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../dashboard.php?db=<?= $active_db ?>">Sistem Akademik</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5>Tambah Data Matakuliah</h5>
                    </div>
                    <div class="card-body">
                        <?php if($message): ?>
                            <div class="alert alert-success"><?= $message ?></div>
                        <?php endif; ?>
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label>Kode Matakuliah</label>
                                <input type="text" name="kodeMK" class="form-control" required maxlength="10">
                            </div>
                            <div class="mb-3">
                                <label>Nama Matakuliah</label>
                                <input type="text" name="namaMK" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>SKS</label>
                                <input type="number" name="sks" class="form-control" required min="1" max="6">
                            </div>
                            <div class="mb-3">
                                <label>Semester</label>
                                <input type="number" name="smt" class="form-control" required min="1" max="8">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="index.php?db=<?= $active_db ?>" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>