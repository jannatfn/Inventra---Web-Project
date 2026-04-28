<?php
require_once __DIR__ . '/../bootstrap.php';
apiAuthCheck();

$user = new User();
$data = $user->getById($_SESSION['user_id']);

if ($data) {
    sendResponse(true, "Profile retrieved", ["user" => $data]);
} else {
    sendResponse(false, "User not found.");
}
?>
