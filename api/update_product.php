<?php
header("Content-Type: application/json");
require_once '../config/database.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $quantity = $_POST['quantity'] ?? 0;

    if (!$id || empty($name) || $price <= 0 || $quantity < 0) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid input data.';
        echo json_encode($response);
        exit;
    }

    $sql = "UPDATE products SET name = ?, price = ?, quantity = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sdii", $name, $price, $quantity, $id);

        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Product updated successfully.';
            } else {
                $response['status'] = 'info';
                $response['message'] = 'No changes made or product not found.';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Could not update product: ' . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Database error: ' . mysqli_error($conn);
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

mysqli_close($conn);
echo json_encode($response);
?>
