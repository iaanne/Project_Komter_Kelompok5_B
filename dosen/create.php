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

// Set default values
if(empty($_POST)) {
    $_POST = [];
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nip = isset($_POST['nip']) ? $_POST['nip'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    
    if(!$nip || !$nama || !$alamat) {
        $error = "Semua field harus diisi!";
    } else {
    
    try {
        $sql = "INSERT INTO dosen (nip, nama, alamat) VALUES (:nip, :nama, :alamat)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nip' => $nip,
            ':nama' => $nama,
            ':alamat' => $alamat
        ]);
        $message = "Data dosen berhasil ditambahkan!";
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
    <title>Tambah Dosen</title>
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
                        <h5>Tambah Data Dosen</h5>
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
                                <label>NIP</label>
                                <input type="text" name="nip" class="form-control" required maxlength="10">
                            </div>
                            <div class="mb-3">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control" required rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="index.php?db=<?= $active_db ?>" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>