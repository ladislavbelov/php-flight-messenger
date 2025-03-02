<?php
use App\Models\UserModel;
use App\Services\AuthService;
use Ramsey\Uuid\Uuid;
use Firebase\JWT\JWT;

// Подключение моделей и сервисов
$userModel = new UserModel(Flight::db());
$authService = new AuthService($_ENV['JWT_SECRET']);

// Глобальный обработчик ошибок
Flight::map('error', function(Throwable $error) {
    error_log($error->getMessage());

    if ($error->getMessage() === "Incorrect email") {
        Flight::halt(400, json_encode(['message' => 'Incorrect email']));
    } elseif ($error->getMessage() === "User not found") {
        Flight::halt(400, json_encode(['message' => 'User not found']));
    } elseif ($error->getMessage() === "Invalid password") {
        Flight::halt(400, json_encode(['message' => 'Invalid password']));
    } else {
        Flight::halt(500, json_encode(['message' => 'Internal Server Error']));
    }
});

// Маршрут для регистрации
Flight::route('POST /register', function() use ($userModel) {
    $request = Flight::request()->data;

    // Валидация данных
    if (empty($request['email']) || empty($request['password'])) {
        Flight::halt(400, json_encode(['message' => 'All fields are required']));
    }

    // Валидация email
    if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
        Flight::halt(400, json_encode(['message' => 'Incorrect email']));
    }

    // Проверка длины пароля
    if (strlen($request['password']) < 8) {
        Flight::halt(400, json_encode(['message' => 'Password must be at least 8 characters long']));
    }

    try {
        // Хеширование пароля
        $passwordHash = password_hash($request['password'], PASSWORD_BCRYPT);

        // Генерация токена активации
        $activationToken = Uuid::uuid4()->toString();

        // Создание пользователя
        $userModel->createUser($request['email'], $passwordHash, $activationToken);

        Flight::json(['message' => 'User registered successfully']);
    } catch (\Exception $e) {
        throw $e; // Ошибка будет обработана глобальным обработчиком
    }
});

Flight::route('POST /login', function() use ($userModel, $authService) {
    $request = Flight::request()->data;

    // Валидация данных
    if (empty($request['email']) || empty($request['password'])) {
        Flight::halt(400, json_encode(['message' => 'Email and password are required']));
    }

    try {
        // Проверка учетных данных
        $user = $userModel->validateUserCredentials($request['email'], $request['password']);

        // Генерация токена
        $token = $authService->generateToken($user['id'], $user['email']);

        // Формирование ответа
        $response = [
            'success' => true,
            'token' => $token,
            'data' => [
                'userId' => $user['id'],
                'email' => $user['email'],
                'roleId' => $user['role_id']
            ],
            'expiresIn' => 3600 // Время жизни токена в секундах
        ];

        // Возврат ответа
        Flight::json($response);
    } catch (\Exception $e) {
        Flight::halt(400, json_encode(['message' => $e->getMessage()]));
    }
});