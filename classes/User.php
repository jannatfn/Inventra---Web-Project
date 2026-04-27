<?php
// classes/User.php
class User extends BaseModel {
    protected $table = "users";

    public function emailExists($email) {
        return $this->fetch("SELECT id FROM {$this->table} WHERE email = ? LIMIT 1", [$email]) !== false;
    }

    public function register($name, $email, $password) {
        if ($this->emailExists($email)) return ["success" => false, "message" => "Email already registered."];
        
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO {$this->table} (name, email, password) VALUES (?, ?, ?)";
        return $this->query($sql, [$name, $email, $hashed]) 
               ? ["success" => true, "message" => "User registered successfully."] 
               : ["success" => false, "message" => "Registration failed."];
    }

    public function login($email, $password) {
        $user = $this->fetch("SELECT id, name, password FROM {$this->table} WHERE email = ? LIMIT 1", [$email]);
        if ($user && password_verify($password, $user['password'])) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            return ["success" => true, "message" => "Login successful.", "user" => $user];
        }
        return ["success" => false, "message" => "Invalid credentials."];
    }

    public function getById($id) {
        return $this->fetch("SELECT id, name, email FROM {$this->table} WHERE id = ? LIMIT 1", [$id]);
    }

    public function updateProfile($id, $name, $email) {
        $exists = $this->fetch("SELECT id FROM {$this->table} WHERE email = ? AND id != ?", [$email, $id]);
        if ($exists) return ["success" => false, "message" => "Email already in use."];

        $res = $this->query("UPDATE {$this->table} SET name = ?, email = ? WHERE id = ?", [$name, $email, $id]);
        if ($res) $_SESSION['user_name'] = $name;
        return $res ? ["success" => true, "message" => "Profile updated."] : ["success" => false, "message" => "Update failed."];
    }

    public function changePassword($id, $current, $new) {
        $user = $this->fetch("SELECT password FROM {$this->table} WHERE id = ?", [$id]);
        if (!password_verify($current, $user['password'])) return ["success" => false, "message" => "Current password incorrect."];

        $hashed = password_hash($new, PASSWORD_DEFAULT);
        return $this->query("UPDATE {$this->table} SET password = ? WHERE id = ?", [$hashed, $id]) 
               ? ["success" => true, "message" => "Password changed."] 
               : ["success" => false, "message" => "Update failed."];
    }
}
?>
