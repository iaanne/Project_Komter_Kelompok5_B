<?php
$host = '100.105.109.53';
$port = '5434';
$dbname = 'komter_db';
$username = 'userKomter';
$password = 'komter26';

try {
    $pdo_pgsql = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo_pgsql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("PostgreSQL Connection failed: " . $e->getMessage());
}
?>