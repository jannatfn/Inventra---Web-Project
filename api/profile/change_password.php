<?php
header("Content-Type: application/json");
session_start();
require_once '../../config/db_connect.php';
require_once '../../classes/User.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$current = $data['current_password'] ?? '';
$new = $data['new_password'] ?? '';

if (empty($current) || empty($new)) {
    echo json_encode(["success" => false, "message" => "Both current and new passwords are required."]);
    exit;
}

if (strlen($new) < 6) {
    echo json_encode(["success" => false, "message" => "New password must be at least 6 characters long."]);
    exit;
}

$user = new User($conn);
$result = $user->changePassword($_SESSION['user_id'], $current, $new);
echo json_encode($result);
?>
