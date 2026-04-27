<?php
// api/test_db.php
require_once 'bootstrap.php';

try {
    $db = Database::getInstance()->getConnection();
    echo json_encode([
        "success" => true,
        "message" => "Backend is OK",
        "database" => DB_NAME
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
