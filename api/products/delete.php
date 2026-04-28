<?php
// api/products/delete.php
require_once __DIR__ . '/../bootstrap.php';
apiAuthCheck();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true) ?: $_POST;
    $id = $data['id'] ?? null;

    if (!$id) {
        sendResponse(false, "ID is required.");
    }

    $product = new Product();
    if ($product->delete($id, $_SESSION['user_id'])) {
        sendResponse(true, "Product deleted successfully.");
    } else {
        sendResponse(false, "Delete failed.");
    }
} else {
    sendResponse(false, "Invalid request method.", [], 405);
}
?>
