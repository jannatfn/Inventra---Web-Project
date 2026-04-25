<?php
// classes/Product.php

class Product {
    private $conn;
    private $table_name = "products";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a product
    public function create($user_id, $name, $price, $quantity) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, name, price, quantity) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$user_id, $name, $price, $quantity]);
    }

    // Read all products for a user
    public function readAll($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    // Update a product
    public function update($id, $user_id, $name, $price, $quantity) {
        $query = "UPDATE " . $this->table_name . " SET name = ?, price = ?, quantity = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$name, $price, $quantity, $id, $user_id]);
    }

    // Delete a product
    public function delete($id, $user_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id, $user_id]);
    }

    // Get dashboard stats
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
}
?>
