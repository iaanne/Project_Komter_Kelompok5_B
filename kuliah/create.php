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

// Ambil data untuk dropdown
$mahasiswa = $pdo->query("SELECT nim, nama FROM mahasiswa")->fetchAll(PDO::FETCH_ASSOC);
$matakuliah = $pdo->query("SELECT kode_mk, nama_mk FROM matakuliah")->fetchAll(PDO::FETCH_ASSOC);
$dosen = $pdo->query("SELECT nip, nama FROM dosen")->fetchAll(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $kode_mk = $_POST['kode_mk'];
    $nip = $_POST['nip'];
    $nilai = $_POST['nilai'];
    
    try {
        $sql = "INSERT INTO kuliah (nim, kode_mk, nip, nilai) VALUES (:nim, :kode_mk, :nip, :nilai)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nim' => $nim,
            ':kode_mk' => $kode_mk,
            ':nip' => $nip,
            ':nilai' => $nilai
        ]);
        $message = "Data kuliah berhasil ditambahkan!";
        header("refresh:2;url=index.php?db=$active_db");
    } catch(PDOException $e) {
        $error = "Gagal menambah data: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kuliah</title>
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
                        <h5>Tambah Data Kuliah</h5>
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
                                <label>Mahasiswa</label>
                                <select name="nim" class="form-control" required>
                                    <option value="">Pilih Mahasiswa</option>
                                    <?php foreach($mahasiswa as $mhs): ?>
                                        <option value="<?= $mhs['nim'] ?>"><?= $mhs['nim'] ?> - <?= $mhs['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Matakuliah</label>
                                <select name="kode_mk" class="form-control" required>
                                    <option value="">Pilih Matakuliah</option>
                                    <?php foreach($matakuliah as $mk): ?>
                                        <option value="<?= $mk['kode_mk'] ?>"><?= $mk['kode_mk'] ?> - <?= $mk['nama_mk'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Dosen</label>
                                <select name="nip" class="form-control" required>
                                    <option value="">Pilih Dosen</option>
                                    <?php foreach($dosen as $dsn): ?>
                                        <option value="<?= $dsn['nip'] ?>"><?= $dsn['nip'] ?> - <?= $dsn['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Nilai</label>
                                <input type="text" name="nilai" class="form-control" required maxlength="2">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="index.php?db=<?= $active_db ?>" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>