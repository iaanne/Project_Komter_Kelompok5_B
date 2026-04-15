<?php
session_start();

class Database {
    private $db_type;
    private $connection;
    
    public function __construct($db_type = 'mysql') {
        $this->db_type = $db_type;
        $this->connect();
    }
    
    private function connect() {
        try {
            switch($this->db_type) {
                case 'mysql':
                    $this->connection = new PDO(
                        "mysql:host=localhost;dbname=project_komter;charset=utf8",
                        "iim",
                        "12345678"
                    );
                    break;
                    
                case 'pgsql':
                    $this->connection = new PDO(
                        "pgsql:host=localhost;port=5432;dbname=akademik",
                        "postgres",
                        "password"
                    );
                    break;
                    
                case 'sqlsrv':
                    $this->connection = new PDO(
                        "sqlsrv:Server=localhost;Database=Project_Komter",
                        "kayo",
                        "kayo123"
                    );
                    break;
                    
                default:
                    throw new Exception("Database type not supported");
            }
            
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function switchDatabase($db_type) {
        $this->db_type = $db_type;
        $this->connect();
    }
}
?>
