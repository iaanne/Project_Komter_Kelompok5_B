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

try {
    $sql = "DELETE FROM matakuliah WHERE kode_mk = :kode_mk";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':kode_mk' => $kode_mk]);
    $_SESSION['message'] = "Data berhasil dihapus!";
} catch(PDOException $e) {
    $_SESSION['error'] = "Gagal hapus data: " . $e->getMessage();
}

header('Location: index.php?db=' . $active_db);
exit();
?>