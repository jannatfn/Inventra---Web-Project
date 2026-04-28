<?php
// api/products/create.php
require_once __DIR__ . '/../bootstrap.php';
apiAuthCheck();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true) ?: $_POST;
    
    $name = trim($data['name'] ?? '');
    $quantity = (int)($data['quantity'] ?? 0);
    $price = (float)($data['price'] ?? 0);

    if (empty($name)) {
        sendResponse(false, "Product name is required.");
    }

    $product = new Product();
    if ($product->create($_SESSION['user_id'], $name, $price, $quantity)) {
        sendResponse(true, "Product added successfully.");
    } else {
        sendResponse(false, "Failed to add product.");
    }
} else {
    sendResponse(false, "Invalid request method.", [], 405);
}
?>
