<?php
require_once 'db_connect.php';

// Check if data was sent via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        die("Please fill all fields.");
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement to prevent SQL injection
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind parameters (s = string)
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed_password);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo "Registration successful!";
        } else {
            echo "Error: " . mysqli_stmt_error($stmt);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
} else {
    echo "Invalid request method.";
}
?>
