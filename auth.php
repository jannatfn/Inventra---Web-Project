<?php
session_start();

// Check if the user is logged in by checking the session variable
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header("Location: login_page.php");
    exit();
}
?>
