<?php
header("Content-Type: application/json");
require_once '../includes/auth.php';
require_once '../config/database.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($name) || empty($email)) {
        $response['status'] = 'error';
        $response['message'] = 'Name and email are required.';
        echo json_encode($response);
        exit;
    }

    $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $user_id);

        if (mysqli_stmt_execute($stmt)) {
            $response['status'] = 'success';
            $response['message'] = 'Profile updated successfully.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Update failed: ' . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Database error.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request.';
}

mysqli_close($conn);
echo json_encode($response);
?>
