<?php
// classes/BaseModel.php
abstract class BaseModel {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute($params);
        return $res ? $stmt : false;
    }

    protected function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    protected function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }
}
?>
