<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTMiddleware {
    private $secretKey;

    public function __construct() {
        $this->secretKey = Env::get('JWT_SECRET', 'default_secret_key'); // Load secret key dari .env
    }

    // Validasi token dari session
    public function validateTokenFromSession() {
        // Periksa apakah session sudah dimulai, jika belum, mulai session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Memeriksa apakah token ada di dalam session
        if (!isset($_SESSION['token'])) {
            return ['error' => true, 'message' => 'Authorization token not found in session'];
        }

        $token = $_SESSION['token']; // Ambil token dari session

        try {
            // Decode token menggunakan secret key
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return ['error' => false, 'payload' => $decoded];  // Mengembalikan payload token yang sudah didekode
        } catch (Exception $e) {
            // Jika token tidak valid atau sudah kedaluwarsa
            return ['error' => true, 'message' => 'Invalid or expired token'];
        }
    }

    // Validasi token dari session untuk halaman login (cek jika sudah login)
    public function validateLoginPage() {
        // Periksa apakah session sudah dimulai, jika belum, mulai session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Jika sudah ada token di session, arahkan ke halaman dashboard
        if (isset($_SESSION['token'])) {
            header("Location: /dashboard");
            exit;
        }
    }
}
?>
