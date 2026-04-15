<?php
$host = '100.82.119.118';
$dbname = 'project_komter';
$username = 'iim';
$password = '12345678';

try {
    $pdo_mysql = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo_mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("MySQL Connection failed: " . $e->getMessage());
}
?>
