<?php
// api/profile/change_password.php
require_once '../bootstrap.php';
apiAuthCheck();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true) ?: $_POST;
    
    $current_password = $data['current_password'] ?? '';
    $new_password = $data['new_password'] ?? '';

    if (empty($current_password) || empty($new_password)) {
        sendResponse(false, "Both current and new passwords are required.");
    }

    if (strlen($new_password) < 6) {
        sendResponse(false, "New password must be at least 6 characters long.");
    }

    $user = new User();
    $result = $user->changePassword($_SESSION['user_id'], $current_password, $new_password);
    
    if ($result['success']) {
        sendResponse(true, $result['message']);
    } else {
        sendResponse(false, $result['message']);
    }
} else {
    sendResponse(false, "Invalid request method.", [], 405);
}
?>
