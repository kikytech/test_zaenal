<?php
require_once __DIR__ . '/../middlewares/JWTMiddleware.php';

class ProtectedController {
    private $jwtMiddleware;

    public function __construct() {
        $this->jwtMiddleware = new JWTMiddleware();
    }

    // Halaman dashboard yang dilindungi
    public function protectedEndpoint() {
        // Validasi token dari session
        $authResponse = $this->jwtMiddleware->validateTokenFromSession();

        // Jika token tidak valid atau tidak ditemukan
        if ($authResponse['error']) {
            http_response_code(401);  // Unauthorized
            header("Location: ".Env::get('URL_BASE')."/login");  // Arahkan ke login
            exit;
        }

        // Jika token valid, kirim data ke view
        $user = $authResponse['payload']; // Payload user dari token
        $content = __DIR__ . '/../views/dashboard.php'; // Render the update form
        

        // Pass the order data and load the layout
        require_once __DIR__ . '/../views/layout.php';
    }

    // Logout dan hapus token dari session
    public function logout() {
        session_start();
        session_unset();  // Hapus semua session variables
        session_destroy();  // Hancurkan session
        header("Location: ".Env::get('URL_BASE')."/login");  // Redirect ke halaman login
        exit;
    }
}
?>
