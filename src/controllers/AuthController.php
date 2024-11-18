<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../../vendor/autoload.php'; // Autoload Composer
require_once __DIR__ . '/../request/RegisterRequest.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthController {
    private $db;
    private $userModel;
    private $secretKey;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->secretKey = Env::get('JWT_SECRET', 'default_secret_key'); // Load secret key dari .env
    }


    public function showLoginForm() {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function showRegisterForm() {
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function register($data) {
        $request = new RegisterRequest($data, $this->db); // Validasi input
    
        if (!$request->validate()) {
            return ['error' => true, 'message' => $request->errors()];
        }
    
        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
    
        // Simpan user baru ke database
        $userCreated = $this->userModel->createUser(
            $data['username'],
            $hashedPassword,
            $data['role']
        );
    
        if (!$userCreated) {
            return ['error' => true, 'message' => 'Failed to register user.'];
        }
    
        // Ambil data user untuk payload JWT
        $user = $this->userModel->findByUsername($data['username']);
    
        // Generate JWT token
        $payload = [
            'sub' => $user['id'], // User ID
            'name' => $user['username'], // Username
            'role' => $user['role'], // User role
            'iat' => time(),
            'exp' => time() + (60 * 60) // Token berlaku selama 1 jam
        ];
    
        $token = JWT::encode($payload, $this->secretKey, 'HS256');
    
        return [
            'error' => false,
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token
        ];
    }
    
    

    public function decodeToken($jwt) {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
            return ['error' => false, 'payload' => $decoded];
        } catch (Exception $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function login($username, $password) {
        $user = $this->userModel->findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            return ['error' => true, 'message' => 'Invalid credentials'];
        }

        return ['error' => false, 'message' => 'Login successful', 'user' => $user];
    }
}
?>
