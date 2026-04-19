<?php
header("Content-Type: application/json");
require_once 'db_connect.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $quantity = $_POST['quantity'] ?? 0;

    if (empty($name) || $price <= 0 || $quantity < 0) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid input data.';
        echo json_encode($response);
        exit;
    }

    $sql = "INSERT INTO products (name, price, quantity) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sdi", $name, $price, $quantity);

        if (mysqli_stmt_execute($stmt)) {
            $response['status'] = 'success';
            $response['message'] = 'Product added successfully.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Could not add product: ' . mysqli_stmt_error($stmt);
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
