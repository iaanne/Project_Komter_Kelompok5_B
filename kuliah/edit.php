<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit();
}

$active_db = $_GET['db'] ?? 'mysql';
$id = $_GET['id'];

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

// Ambil data kuliah
$sql = "SELECT * FROM kuliah WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$k = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$k) {
    header('Location: index.php?db=' . $active_db);
    exit();
}

// Ambil data untuk dropdown
$mahasiswa = $pdo->query("SELECT nim, nama FROM mahasiswa")->fetchAll(PDO::FETCH_ASSOC);
$matakuliah = $pdo->query("SELECT kodeMK, namaMK FROM matkul")->fetchAll(PDO::FETCH_ASSOC);
$dosen = $pdo->query("SELECT nip, nama FROM dosen")->fetchAll(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $kodeMK = $_POST['kodeMK'];
    $nip = $_POST['nip'];
    $nilai = $_POST['nilai'];
    
    try {
        $sql = "UPDATE kuliah SET nim = :nim, kodeMK = :kodeMK, nip = :nip, nilai = :nilai WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nim' => $nim,
            ':kodeMK' => $kodeMK,
            ':nip' => $nip,
            ':nilai' => $nilai,
            ':id' => $id
        ]);
        $message = "Data kuliah berhasil diupdate!";
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
    <title>Edit Kuliah</title>
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
                        <h5>Edit Data Kuliah</h5>
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
                                <label>ID</label>
                                <input type="text" class="form-control" value="<?= $k['id'] ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label>Mahasiswa</label>
                                <select name="nim" class="form-control" required>
                                    <option value="">Pilih Mahasiswa</option>
                                    <?php foreach($mahasiswa as $mhs): ?>
                                        <option value="<?= $mhs['nim'] ?>" <?= $mhs['nim'] == $k['nim'] ? 'selected' : '' ?>><?= $mhs['nim'] ?> - <?= $mhs['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Matakuliah</label>
                                <select name="kodeMK" class="form-control" required>
                                    <option value="">Pilih Matakuliah</option>
                                    <?php foreach($matakuliah as $mk): ?>
                                        <option value="<?= $mk['kodeMK'] ?>" <?= $mk['kodeMK'] == $k['kodeMK'] ? 'selected' : '' ?>><?= $mk['kodeMK'] ?> - <?= $mk['namaMK'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Dosen</label>
                                <select name="nip" class="form-control" required>
                                    <option value="">Pilih Dosen</option>
                                    <?php foreach($dosen as $dsn): ?>
                                        <option value="<?= $dsn['nip'] ?>" <?= $dsn['nip'] == $k['nip'] ? 'selected' : '' ?>><?= $dsn['nip'] ?> - <?= $dsn['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Nilai</label>
                                <input type="text" name="nilai" class="form-control" value="<?= $k['nilai'] ?>" required maxlength="2">
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="index.php?db=<?= $active_db ?>" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>