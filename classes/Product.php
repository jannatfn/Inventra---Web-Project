<?php
// classes/Product.php

class Product {
    private $conn;
    private $table_name = "products";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get dashboard statistics
    public function getStats($user_id) {
        $query = "SELECT 
                    COUNT(*) as total_items,
                    SUM(price * quantity) as total_value,
                    SUM(CASE WHEN quantity < 5 THEN 1 ELSE 0 END) as low_stock_count
                  FROM " . $this->table_name . "
                  WHERE user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }

    // List all products (existing method from previous steps)
    public function readAll($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt;
    }
}
?>
