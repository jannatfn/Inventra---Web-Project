<?php
// api/register.php
header("Content-Type: application/json");
require_once '../config/db_connect.php';
require_once '../classes/User.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get POST data (supporting both Form Data and JSON)
    $data = json_decode(file_get_contents("php://input"), true) ?: $_POST;

    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = trim($data['password'] ?? '');

    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
        exit;
    }

    $user = new User($conn);
    $result = $user->register($name, $email, $password);
    echo json_encode($result);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
