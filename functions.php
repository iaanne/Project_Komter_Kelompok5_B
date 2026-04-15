<?php
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function getActiveDB() {
    return $_GET['db'] ?? $_SESSION['active_db'] ?? 'mysql';
}

function setActiveDB($db) {
    $_SESSION['active_db'] = $db;
}

function getConnection($db_type) {
    switch($db_type) {
        case 'pgsql':
            global $pdo_pgsql;
            return $pdo_pgsql;
        case 'sqlsrv':
            global $pdo_sqlsrv;
            return $pdo_sqlsrv;
        default:
            global $pdo_mysql;
            return $pdo_mysql;
    }
}
?>
