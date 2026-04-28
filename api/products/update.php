<?php
// api/products/update.php
require_once __DIR__ . '/../bootstrap.php';
apiAuthCheck();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true) ?: $_POST;
    
    $id = $data['id'] ?? null;
    $name = trim($data['name'] ?? '');
    $quantity = (int)($data['quantity'] ?? 0);
    $price = (float)($data['price'] ?? 0);

    if (!$id || empty($name)) {
        sendResponse(false, "ID and name are required.");
    }

    $product = new Product();
    if ($product->update($id, $_SESSION['user_id'], $name, $price, $quantity)) {
        sendResponse(true, "Product updated successfully.");
    } else {
        sendResponse(false, "Update failed.");
    }
} else {
    sendResponse(false, "Invalid request method.", [], 405);
}
?>
