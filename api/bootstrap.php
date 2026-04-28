<?php
// api/bootstrap.php
ob_start(); // Start buffering immediately
session_start();

// Disable error display to prevent breaking JSON, but log them
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/autoload.php';

// Standardized response helper
function sendResponse($success, $message, $data = [], $code = 200) {
    // Clear any stray output (notices, warnings) before sending JSON
    if (ob_get_level() > 0) ob_clean(); 
    
    header("Content-Type: application/json");
    http_response_code($code);
    echo json_encode(array_merge([
        "success" => $success,
        "message" => $message
    ], $data));
    exit;
}

// Global Exception Handler to ensure we ALWAYS return JSON
set_exception_handler(function($e) {
    sendResponse(false, "System Error: " . $e->getMessage(), [], 500);
});

function apiAuthCheck() {
    if (!isset($_SESSION['user_id'])) {
        sendResponse(false, "Unauthorized access", [], 401);
    }
}
