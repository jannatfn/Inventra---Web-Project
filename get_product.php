<?php
header("Content-Type: application/json");
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($product = mysqli_fetch_assoc($result)) {
            echo json_encode($product);
        } else {
            echo json_encode(array("error" => "Product not found."));
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(array("error" => "Database error."));
    }
} else {
    echo json_encode(array("error" => "No ID provided."));
}

mysqli_close($conn);
?>
