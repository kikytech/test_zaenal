<?php
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/controllers/AuthController.php';

// Tangkap URL path
$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Deteksi folder root proyek secara otomatis
$scriptName = dirname($_SERVER['SCRIPT_NAME']); // Folder tempat `index.php` berada
$requestPath = str_replace($scriptName, '', $requestPath); // Hilangkan folder root

// Debugging untuk memastikan path sudah bersih
error_log("Processed Path: $requestPath");

// Routing
switch (trim($requestPath, '/')) {
    case 'login':
        $authController = new AuthController($db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = $authController->login($_POST['username'], $_POST['password']);

            if ($response['error']) {
                $message = $response['message'];
            } else {
                session_start();
                $_SESSION['user'] = $response['user'];
                header('Location: /');
                exit;
            }
        }

        $authController->showLoginForm();
        break;

        case 'register':
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
        
            // Tampilkan form register
            require_once __DIR__ . '/src/views/auth/register.php';
            break;
        
        
    default:
        http_response_code(404);
        echo "404 - Page Not Found";
        break;
}
?>
