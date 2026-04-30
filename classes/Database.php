<?php
// classes/Database.php

class Database {
    private static $instance = null;
    private $conn = null;

    private function __construct() {
        // Connection logic using global constants
        try {
            $this->conn = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            // Log for debugging
            $errorMsg = $e->getMessage();
            
            if (strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false) {
                header("Content-Type: application/json");
                http_response_code(500);
                echo json_encode(["success" => false, "message" => "Database Connection Failed: " . $errorMsg]);
                exit;
            }
            die("Service Unavailable: Database connection failed. <br><br><b>Technical Error:</b> " . $errorMsg);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}
