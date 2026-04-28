<?php
// api/products/read.php
require_once __DIR__ . '/../bootstrap.php';
apiAuthCheck();

$product = new Product();
$data = $product->readAll($_SESSION['user_id']);
sendResponse(true, "Products retrieved", ["products" => $data]);
?>
