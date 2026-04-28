<?php
// api/get_stats.php
require_once __DIR__ . '/bootstrap.php';
apiAuthCheck();

$product = new Product();
$stats = $product->getStats($_SESSION['user_id']);
sendResponse(true, "Stats retrieved", [
    "stats" => [
        "total_items" => (int)$stats['total_items'],
        "total_value" => (float)($stats['total_value'] ?? 0),
        "low_stock" => (int)($stats['low_stock_count'] ?? 0)
    ]
]);
?>
