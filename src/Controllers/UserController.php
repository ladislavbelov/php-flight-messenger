<?php
use Ramsey\Uuid\Uuid;

class UserController {
    private $userModel;
    private $authService;

    public function __construct($userModel, $authService) {
        $this->userModel = $userModel;
        $this->authService = $authService;
    }

    public function register($email,$password) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $activationToken = Uuid::uuid4()->toString();
        $this->userModel->createUser($email, $passwordHash, $activationToken);
    }

    public function login($email, $password) {
        $user = $this->userModel->findUserByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            throw new Exception("Invalid credentials");
        }
        return $this->authService->generateToken($user['id'], $user['email']);
    }
}