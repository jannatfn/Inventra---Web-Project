<?php
// api/bootstrap.php
session_start();
require_once __DIR__ . '/../config/autoload.php';

function sendResponse($success, $message, $data = [], $code = 200) {
    if (ob_get_length()) ob_clean(); // Aggressively clear any stray output
    header("Content-Type: application/json");
    http_response_code($code);
    echo json_encode(array_merge([
        "success" => $success,
        "message" => $message
    ], $data));
    exit;
}

function apiAuthCheck() {
    if (!isset($_SESSION['user_id'])) {
        sendResponse(false, "Unauthorized", [], 401);
    }
}
