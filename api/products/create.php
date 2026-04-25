<?php
header("Content-Type: application/json");
session_start();
require_once '../../config/db_connect.php';
require_once '../../classes/Product.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true) ?: $_POST;

$name = trim($data['name'] ?? '');
$price = (float)($data['price'] ?? 0);
$quantity = (int)($data['quantity'] ?? 0);

if (empty($name) || $price <= 0 || $quantity < 0) {
    echo json_encode(["success" => false, "message" => "Invalid input data."]);
    exit;
}

$product = new Product($conn);
if ($product->create($_SESSION['user_id'], $name, $price, $quantity)) {
    echo json_encode(["success" => true, "message" => "Product added successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add product."]);
}
?>
