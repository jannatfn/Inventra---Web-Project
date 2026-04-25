<?php
header("Content-Type: application/json");
session_start();
require_once '../../config/db_connect.php';
require_once '../../classes/Product.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$product = new Product($conn);
$data = $product->readAll($_SESSION['user_id']);
echo json_encode($data);
?>
