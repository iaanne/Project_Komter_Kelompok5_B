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

// Ambil data kuliah dengan join
if($active_db == 'sqlsrv') {
    $sql = "SELECT k.id, k.nim, m.nama as nama_mhs, k.kode_mk, mk.nama_mk, k.nip, d.nama as nama_dsn, k.nilai 
            FROM kuliah k 
            JOIN mahasiswa m ON k.nim = m.nim 
            JOIN matakuliah mk ON k.kode_mk = mk.kode_mk 
            JOIN dosen d ON k.nip = d.nip";
} else {
    $sql = "SELECT k.id, k.nim, m.nama as nama_mhs, k.kode_mk, mk.nama_mk, k.nip, d.nama as nama_dsn, k.nilai 
            FROM kuliah k 
            JOIN mahasiswa m ON k.nim = m.nim 
            JOIN matakuliah mk ON k.kode_mk = mk.kode_mk 
            JOIN dosen d ON k.nip = d.nip";
}

$stmt = $pdo->query($sql);
$kuliah = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Kuliah</title>
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
                <h5>Data Kuliah - <?= strtoupper($active_db) ?></h5>
            </div>
            <div class="card-body">
                <a href="create.php?db=<?= $active_db ?>" class="btn btn-success mb-3">Tambah Kuliah</a>
                
                <table class="table table-bordered">
                    <thead>
                        <tr><th>ID</th><th>Mahasiswa</th><th>Matakuliah</th><th>Dosen</th><th>Nilai</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($kuliah as $k): ?>
                        <tr>
                            <td><?= htmlspecialchars($k['id']) ?></td>
                            <td><?= htmlspecialchars($k['nim'] . ' - ' . $k['nama_mhs']) ?></td>
                            <td><?= htmlspecialchars($k['kode_mk'] . ' - ' . $k['nama_mk']) ?></td>
                            <td><?= htmlspecialchars($k['nip'] . ' - ' . $k['nama_dsn']) ?></td>
                            <td><?= htmlspecialchars($k['nilai']) ?></td>
                            <td>
                                <a href="edit.php?db=<?= $active_db ?>&id=<?= $k['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete.php?db=<?= $active_db ?>&id=<?= $k['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>