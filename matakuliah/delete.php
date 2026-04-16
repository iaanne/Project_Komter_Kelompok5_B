<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit();
}

$active_db = $_GET['db'] ?? 'mysql';
$kodeMK = $_GET['kodeMK'];

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
    $sql = "DELETE FROM matkul WHERE kodeMK = :kodeMK";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':kodeMK' => $kodeMK]);
    $_SESSION['message'] = "Data berhasil dihapus!";
} catch(PDOException $e) {
    $_SESSION['error'] = "Gagal hapus data: " . $e->getMessage();
}

header('Location: index.php?db=' . $active_db);
exit();
?>