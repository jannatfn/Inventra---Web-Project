<?php
// config/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user session is not found
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
