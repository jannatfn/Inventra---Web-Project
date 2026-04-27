<?php
// config/db_connect.php
require_once __DIR__ . '/config.php';

try {
    $conn = new PDO(
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
    if (strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false) {
        header("Content-Type: application/json");
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Database Error"]);
        exit;
    }
    die("Service Unavailable.");
}
