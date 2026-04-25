<?php
header("Content-Type: application/json");
session_start();
require_once '../../config/db_connect.php';
require_once '../../classes/Product.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id = (int)($data['id'] ?? 0);

if (!$id) {
    echo json_encode(["success" => false, "message" => "ID is required."]);
    exit;
}

$product = new Product($conn);
if ($product->delete($id, $_SESSION['user_id'])) {
    echo json_encode(["success" => true, "message" => "Product deleted."]);
} else {
    echo json_encode(["success" => false, "message" => "Delete failed."]);
}
?>
