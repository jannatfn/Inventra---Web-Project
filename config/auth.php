<?php
// config/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/autoload.php';

// Redirect to login if user session is not found
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Security: Verify user still exists in DB (fixes errors after DB reset/re-import)
require_once __DIR__ . '/autoload.php';
$userModel = new User();
if (!$userModel->getById($_SESSION['user_id'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
