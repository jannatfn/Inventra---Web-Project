<?php
// classes/Database.php
require_once __DIR__ . '/../config/db_connect.php';

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        if ($this->conn === null) {
            require_once __DIR__ . '/../config/db_connect.php';
            global $conn;
            $this->conn = $conn;
        }
        return $this->conn;
    }
}
?>
