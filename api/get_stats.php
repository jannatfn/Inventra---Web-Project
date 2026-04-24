<?php
// api/get_stats.php
header("Content-Type: application/json");
session_start();

require_once '../config/db_connect.php';
require_once '../classes/Product.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$product = new Product($conn);
$stats = $product->getStats($_SESSION['user_id']);

echo json_encode([
    "success" => true,
    "stats" => [
        "total_items" => (int)$stats['total_items'],
        "total_value" => (float)($stats['total_value'] ?? 0),
        "low_stock" => (int)($stats['low_stock_count'] ?? 0)
    ]
]);
?>
