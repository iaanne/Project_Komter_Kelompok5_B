<?php
$host = '100.76.65.77';
$dbname = 'Project_Komter';
$username = 'kayo';
$password = 'kayo123';

try {
    $pdo_sqlsrv = new PDO("sqlsrv:Server=$host;Database=$dbname", $username, $password);
    $pdo_sqlsrv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("SQL Server Connection failed: " . $e->getMessage());
}
?>
