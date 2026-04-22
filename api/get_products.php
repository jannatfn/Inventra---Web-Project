<?php
header("Content-Type: application/json");
require_once '../config/database.php';

$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

if ($result) {
    $products = array();
    
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
    
    echo json_encode($products);
} else {
    echo json_encode(array("error" => mysqli_error($conn)));
}

mysqli_close($conn);
?>
