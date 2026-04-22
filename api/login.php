<?php
// api/login.php
header("Content-Type: application/json");
require_once '../config/db_connect.php';
require_once '../classes/User.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true) ?: $_POST;

    $email = trim($data['email'] ?? '');
    $password = trim($data['password'] ?? '');

    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Email and password are required."]);
        exit;
    }

    $user = new User($conn);
    $result = $user->login($email, $password);
    echo json_encode($result);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
