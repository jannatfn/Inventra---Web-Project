<?php
header("Content-Type: application/json");
require_once '../includes/auth.php';
require_once '../config/database.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        echo json_encode($user);
    } else {
        echo json_encode(array("error" => "User not found."));
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(array("error" => "Database error."));
}

mysqli_close($conn);
?>
