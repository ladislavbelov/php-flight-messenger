<?php
namespace App\Models;

use PDO;
use PDOException;

class UserModel {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createUser($email, $passwordHash, $activationToken) {
        // Проверка, существует ли пользователь с таким email
        $existingUser = $this->findUserByEmail($email);
        if ($existingUser) {
            throw new \Exception("Incorrect email");
        }

        // Вставка нового пользователя
        $stmt = $this->db->prepare("INSERT INTO users (email, password_hash, activation_token, role_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$email, $passwordHash, $activationToken, 3]);
    }

    public function findUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function validateUserCredentials($email, $password) {
        $user = $this->findUserByEmail($email);
        if (!$user) {
            throw new \Exception("User not found");
        }

        // Проверка пароля
        if (!password_verify($password, $user['password_hash'])) {
            throw new \Exception("Invalid password");
        }

        return $user;
    }
}