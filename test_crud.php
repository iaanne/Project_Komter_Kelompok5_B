<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

$active_db = $_GET['db'] ?? 'mysql';

switch($active_db) {
    case 'pgsql':
        require_once 'config/pgsql.php';
        $pdo = $pdo_pgsql;
        break;
    case 'sqlsrv':
        require_once 'config/sqlsrv.php';
        $pdo = $pdo_sqlsrv;
        break;
    default:
        require_once 'config/mysql.php';
        $pdo = $pdo_mysql;
}

// Ambil data
$stmt = $pdo->query("SELECT * FROM mahasiswa");
$mahasiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Test CRUD - Database: <?= strtoupper($active_db) ?></h1>
        
        <?php if(count($mahasiswa) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr><th>NIM</th><th>Nama</th><th>Alamat</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php foreach($mahasiswa as $mhs): ?>
                    <tr>
                        <td><?= $mhs['nim'] ?></td>
                        <td><?= $mhs['nama'] ?></td>
                        <td><?= $mhs['alamat'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm">Edit</button>
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">Tidak ada data mahasiswa</div>
        <?php endif; ?>
        
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        <a href="test_crud.php?db=mysql" class="btn btn-primary">MySQL</a>
        <a href="test_crud.php?db=pgsql" class="btn btn-primary">PostgreSQL</a>
        <a href="test_crud.php?db=sqlsrv" class="btn btn-primary">SQL Server</a>
    </div>
</body>
</html>