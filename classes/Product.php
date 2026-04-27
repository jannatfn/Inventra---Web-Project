<?php
// classes/Product.php
class Product extends BaseModel {
    protected $table = "products";

    public function create($user_id, $name, $price, $quantity) {
        $sql = "INSERT INTO {$this->table} (user_id, name, price, quantity) VALUES (?, ?, ?, ?)";
        return $this->query($sql, [$user_id, $name, $price, $quantity]);
    }

    public function readAll($user_id) {
        return $this->fetchAll("SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC", [$user_id]);
    }

    public function update($id, $user_id, $name, $price, $quantity) {
        $sql = "UPDATE {$this->table} SET name = ?, price = ?, quantity = ? WHERE id = ? AND user_id = ?";
        return $this->query($sql, [$name, $price, $quantity, $id, $user_id]);
    }

    public function delete($id, $user_id) {
        return $this->query("DELETE FROM {$this->table} WHERE id = ? AND user_id = ?", [$id, $user_id]);
    }

    public function getStats($user_id) {
        return $this->fetch("SELECT 
                    COUNT(*) as total_items,
                    SUM(price * quantity) as total_value,
                    SUM(CASE WHEN quantity < " . LOW_STOCK_THRESHOLD . " THEN 1 ELSE 0 END) as low_stock_count
                  FROM {$this->table}
                  WHERE user_id = ?", [$user_id]);
    }
}
?>
