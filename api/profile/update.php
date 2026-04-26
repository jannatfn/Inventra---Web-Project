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
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');

if (empty($name) || empty($email)) {
    echo json_encode(["success" => false, "message" => "Name and email are required."]);
    exit;
}

$user = new User($conn);
$result = $user->updateProfile($_SESSION['user_id'], $name, $email);
echo json_encode($result);
?>
