<?php
// Memulai autoloading dan dependensi
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/controllers/AuthController.php';
require_once __DIR__ . '/src/controllers/ProtectedController.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Deteksi folder root proyek secara otomatis
$scriptName = dirname($_SERVER['SCRIPT_NAME']); // Folder tempat `index.php` berada
$requestUri = $_SERVER['REQUEST_URI']; // Ambil URL penuh
$requestPath = parse_url($requestUri, PHP_URL_PATH); // Ambil path URL
$requestPath = str_replace($scriptName, '', $requestPath); // Hilangkan folder root dari path

// Debugging untuk memastikan path sudah bersih
error_log("Processed Path: $requestPath");

// Mulai sesi untuk memeriksa token JWT
// Middleware untuk cek login pada halaman yang dilindungi
$jwtMiddleware = new JWTMiddleware();

switch (trim($requestPath, '/')) {
    case 'login':
        // Jika sudah login, alihkan ke dashboard
        if (isset($_SESSION['token'])) {
            header('Location: '.Env::get('URL_BASE').'/dashboard');  // Jika sudah login, jangan biarkan akses login lagi
            exit;
        }
        
        $authController = new AuthController($db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = $authController->login($_POST);

            if ($response['error']) {
                $errors = $response['message'];
            } else {
                $_SESSION['user'] = $response['user'];
                $_SESSION['token'] = $response['token'];  // Menyimpan JWT token di session
                header('Location: '.Env::get('URL_BASE').'/dashboard');  // Redirect ke dashboard setelah login berhasil
                exit;
            }
        }
    
        $authController->showLoginForm();
        break;

    case 'logout':
        // Logout, hapus session
        $protectedController = new ProtectedController();
        $protectedController->logout();  // Hapus session dan redirect ke login
        break;
        
    case 'dashboard':
        // Menggunakan middleware untuk memeriksa apakah pengguna sudah login
        $authResponse = $jwtMiddleware->validateTokenFromSession();
        
        if ($authResponse['error']) {
            header('Location: '.Env::get('URL_BASE').'/login');  // Jika tidak valid, arahkan ke login
            exit;
        }

        $protectedController = new ProtectedController();
        $protectedController->protectedEndpoint();  // Tampilkan halaman dashboard
        break;

    case 'register':
        // Jika sudah login, alihkan ke dashboard
        if (isset($_SESSION['token'])) {
            header('Location: '.Env::get('URL_BASE').'/dashboard');  // Jika sudah login, jangan biarkan akses register lagi
            exit;
        }

        $authController = new AuthController($db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = $authController->register($_POST);

            if ($response['error']) {
                $errors = $response['message'];
                $validationErrors = $response['validationErrors'] ?? [];
            } else {
                $successMessage = $response['message'];
                $token = $response['token'];
            }
        }
    
        require_once __DIR__ . '/src/views/auth/register.php'; // Tampilkan form register
        break;

    case 'logout':
        // Logout, hapus session
        session_unset();
        session_destroy();
        header('Location: '.Env::get('URL_BASE').'/login');  // Arahkan ke halaman login setelah logout
        exit;

    default:
        http_response_code(404);
        echo "404 - Page Not Found";
        break;
}
?>
