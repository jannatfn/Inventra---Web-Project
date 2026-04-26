<?php
header("Content-Type: application/json");
session_start();
require_once '../../config/db_connect.php';
require_once '../../classes/User.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$user = new User($conn);
$data = $user->getById($_SESSION['user_id']);

if ($data) {
    echo json_encode(["success" => true, "user" => $data]);
} else {
    echo json_encode(["success" => false, "message" => "User not found."]);
}
?>
