<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit();
}

$active_db = $_GET['db'] ?? 'mysql';
$nim = $_GET['nim'];

switch($active_db) {
    case 'pgsql':
        require_once '../config/postgresql.php';
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

try {
    $sql = "DELETE FROM mahasiswa WHERE nim = :nim";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nim' => $nim]);
    $_SESSION['message'] = "Data berhasil dihapus!";
} catch(PDOException $e) {
    $_SESSION['error'] = "Gagal hapus data: " . $e->getMessage();
}

header('Location: index.php?db=' . $active_db);
exit();
?>
