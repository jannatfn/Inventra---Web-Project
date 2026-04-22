<?php
header("Content-Type: application/json");
require_once '../config/database.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        $response['status'] = 'error';
        $response['message'] = 'Product ID is required.';
        echo json_encode($response);
        exit;
    }

    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Product deleted successfully.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Product not found.';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Could not delete product: ' . mysqli_stmt_error($stmt);
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
