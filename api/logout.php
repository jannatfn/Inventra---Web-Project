<?php
// api/logout.php
require_once 'bootstrap.php';

session_unset();
session_destroy();

sendResponse(true, "Logged out successfully.");
?>
