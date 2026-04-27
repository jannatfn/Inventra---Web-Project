<?php
ob_start();
require_once 'bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ob_clean();
    sendResponse(false, "Invalid method", [], 405);
}

$data = json_decode(file_get_contents("php://input"), true) ?: $_POST;
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($email) || empty($password)) {
    ob_clean();
    sendResponse(false, "Email and password required");
}

$user = new User();
$result = $user->login($email, $password);

ob_clean();
if ($result['success']) {
    sendResponse(true, "Login successful", ["user" => $result['user']]);
} else {
    sendResponse(false, $result['message'], [], 401);
}
?>
