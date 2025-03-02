<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    private $jwtSecret;

    public function __construct($jwtSecret) {
        $this->jwtSecret = $jwtSecret;
    }

    public function authenticate() {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            Flight::halt(401, json_encode(['message' => 'Authorization header is missing']));
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            Flight::set('user', $decoded);
        } catch (Exception $e) {
            Flight::halt(401, json_encode(['message' => 'Invalid token']));
        }
    }
}