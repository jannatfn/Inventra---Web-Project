<?php
// classes/Response.php

class Response {
    // Send a standard success response
    public static function success($message = "Success", $data = []) {
        header("Content-Type: application/json");
        echo json_encode(array_merge([
            "success" => true,
            "message" => $message
        ], $data));
        exit;
    }

    // Send a standard error response
    public static function error($message = "An error occurred", $code = 400) {
        header("Content-Type: application/json");
        http_response_code($code);
        echo json_encode([
            "success" => false,
            "message" => $message
        ]);
        exit;
    }
}
?>
