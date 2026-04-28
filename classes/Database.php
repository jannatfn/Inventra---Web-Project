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
            // If this is an API call, return JSON. Otherwise, show a plain message.
            if (strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false) {
                header("Content-Type: application/json");
                http_response_code(500);
                echo json_encode(["success" => false, "message" => "Database Connection Failed"]);
                exit;
            }
            die("Service Unavailable: Database connection failed.");
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
