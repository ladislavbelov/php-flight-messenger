<?php
namespace App\Services;

use Firebase\JWT\JWT;

class AuthService {
    private $jwtSecret;

    public function __construct($jwtSecret) {
        $this->jwtSecret = $jwtSecret;
    }

    public function generateToken($userId, $email) {
        $payload = [
            'user_id' => $userId,
            'email' => $email,
            'exp' => time() + 36000000 
        ];
        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }
}