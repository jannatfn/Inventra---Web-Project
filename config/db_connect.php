<?php
// config/db_connect.php

$host = "localhost";
$db_name = "inventra_db";
$username = "root";
$password = "";

try {
    // Create PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    
    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set fetch mode to associative array by default
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Determine if it's an API call or a direct page load
    if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
        header("Content-Type: application/json");
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Database Connection Error"]);
        exit;
    }
    // For direct pages, show a user-friendly message
    die("<h3>Service Unavailable</h3><p>We're experiencing technical difficulties. Please try again later.</p>");
}
?>
