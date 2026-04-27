<?php
ob_start();
require_once 'bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ob_clean();
    sendResponse(false, "Invalid method", [], 405);
}

$data = json_decode(file_get_contents("php://input"), true) ?: $_POST;

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($name) || empty($email) || empty($password)) {
    ob_clean();
    sendResponse(false, "All fields are required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    ob_clean();
    sendResponse(false, "Invalid email format.");
}

$user = new User();
$result = $user->register($name, $email, $password);

ob_clean();
if ($result['success']) {
    sendResponse(true, $result['message']);
} else {
    sendResponse(false, $result['message']);
}
?>
