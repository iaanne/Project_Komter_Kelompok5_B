<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

// Ambil data
try {
    $stmt = $pdo->query("SELECT * FROM matkul");
    $matakuliah = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $matakuliah = [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Matakuliah</title>
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
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>Data Matakuliah - <?= strtoupper($active_db) ?></h5>
            </div>
            <div class="card-body">
                <a href="create.php?db=<?= $active_db ?>" class="btn btn-success mb-3">Tambah Matakuliah</a>
                
                <table class="table table-bordered">
                    <thead>
                        <tr><th>Kode MK</th><th>Nama Matakuliah</th><th>SKS</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php if(count($matakuliah) > 0): ?>
                            <?php foreach($matakuliah as $mk): ?>
                            <tr>
                                <td><?= htmlspecialchars($mk['kodeMK'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($mk['namaMK'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($mk['sks'] ?? '-') ?></td>
                                <td>
                                    <a href="edit.php?db=<?= $active_db ?>&kodeMK=<?= $mk['kodeMK'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete.php?db=<?= $active_db ?>&kodeMK=<?= $mk['kodeMK'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')">Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">Tidak ada data matakuliah.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>