<?php
// config/config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'inventra_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('APP_NAME', 'Inventra');
define('LOW_STOCK_THRESHOLD', 5);

// Temporarily ENABLE errors to find the "Unreachable" cause
error_reporting(E_ALL);
ini_set('display_errors', 1);
