<?php
require_once __DIR__ . '/../bootstrap.php';
apiAuthCheck();

$data = json_decode(file_get_contents("php://input"), true) ?: $_POST;
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');

if (empty($name) || empty($email)) {
    sendResponse(false, "Name and email are required.");
}

$user = new User();
$result = $user->updateProfile($_SESSION['user_id'], $name, $email);
sendResponse($result['success'], $result['message']);
?>
