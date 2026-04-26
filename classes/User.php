<?php
// classes/User.php

class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Check if email already exists
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    }

    // Register a new user
    public function register($name, $email, $password) {
        if ($this->emailExists($email)) {
            return ["success" => false, "message" => "Email already registered."];
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO " . $this->table_name . " (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        try {
            if ($stmt->execute([$name, $email, $hashed_password])) {
                return ["success" => true, "message" => "User registered successfully."];
            }
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Registration failed: " . $e->getMessage()];
        }

        return ["success" => false, "message" => "Registration failed."];
    }

    // Login user
    public function login($email, $password) {
        $query = "SELECT id, name, password FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            if (password_verify($password, $row['password'])) {
                // Start session and store user data
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $email;
                
                return ["success" => true, "message" => "Login successful.", "user" => ["id" => $row['id'], "name" => $row['name']]];
            }
        }

        return ["success" => false, "message" => "Invalid email or password."];
    }

    // Get single user data
    public function getById($id) {
        $query = "SELECT id, name, email FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Update profile (name, email)
    public function updateProfile($id, $name, $email) {
        // Check if new email is taken by someone else
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? AND id != ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email, $id]);
        if ($stmt->rowCount() > 0) {
            return ["success" => false, "message" => "Email already in use."];
        }

        $query = "UPDATE " . $this->table_name . " SET name = ?, email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute([$name, $email, $id])) {
            $_SESSION['user_name'] = $name; // Sync session
            return ["success" => true, "message" => "Profile updated successfully."];
        }
        return ["success" => false, "message" => "Update failed."];
    }

    // Change password
    public function changePassword($id, $current_password, $new_password) {
        $query = "SELECT password FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!password_verify($current_password, $user['password'])) {
            return ["success" => false, "message" => "Current password incorrect."];
        }

        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table_name . " SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute([$hashed, $id])) {
            return ["success" => true, "message" => "Password changed successfully."];
        }
        return ["success" => false, "message" => "Update failed."];
    }
}
?>
