<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit();
}

$active_db = $_GET['db'] ?? 'mysql';
$kode_mk = $_GET['kode_mk'];

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

// Ambil data matakuliah
$sql = "SELECT * FROM matakuliah WHERE kode_mk = :kode_mk";
$stmt = $pdo->prepare($sql);
$stmt->execute([':kode_mk' => $kode_mk]);
$mk = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$mk) {
    header('Location: index.php?db=' . $active_db);
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_mk = $_POST['nama_mk'];
    $sks = $_POST['sks'];
    
    try {
        $sql = "UPDATE matakuliah SET nama_mk = :nama_mk, sks = :sks WHERE kode_mk = :kode_mk";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nama_mk' => $nama_mk,
            ':sks' => $sks,
            ':kode_mk' => $kode_mk
        ]);
        $message = "Data matakuliah berhasil diupdate!";
        header("refresh:2;url=index.php?db=$active_db");
    } catch(PDOException $e) {
        $error = "Gagal update data: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Matakuliah</title>
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
                    <div class="card-header bg-warning">
                        <h5>Edit Data Matakuliah</h5>
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
                                <input type="text" class="form-control" value="<?= $mk['kode_mk'] ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label>Nama Matakuliah</label>
                                <input type="text" name="nama_mk" class="form-control" value="<?= $mk['nama_mk'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>SKS</label>
                                <input type="number" name="sks" class="form-control" value="<?= $mk['sks'] ?>" required min="1" max="6">
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="index.php?db=<?= $active_db ?>" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>