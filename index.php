<?php
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/controllers/AuthController.php';
require_once __DIR__ . '/src/controllers/CustomerController.php';
require_once __DIR__ . '/src/controllers/OrderController.php';
require_once __DIR__ . '/src/controllers/ProtectedController.php';

// Deteksi folder root proyek secara otomatis
$scriptName = dirname($_SERVER['SCRIPT_NAME']); // Folder tempat `index.php` berada
$requestUri = $_SERVER['REQUEST_URI']; // Ambil URL penuh
$requestPath = parse_url($requestUri, PHP_URL_PATH); // Ambil path URL
$requestPath = str_replace($scriptName, '', $requestPath); // Hilangkan folder root dari path

// Mulai sesi untuk memeriksa token JWT
session_start();

// Middleware untuk cek login pada halaman yang dilindungi
$jwtMiddleware = new JWTMiddleware();
$authResponse = $jwtMiddleware->validateTokenFromSession();
switch (trim($requestPath, '/')) {
    // Halaman login
    case 'login':
        if (isset($_SESSION['token'])) {
            header('Location: '.Env::get('URL_BASE').'/dashboard');
            exit;
        }

        $authController = new AuthController($db);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = $authController->login($_POST);

            if ($response['error']) {
                $errors = $response['message'];
            } else {
                $_SESSION['user'] = $response['user'];
                $_SESSION['token'] = $response['token'];
                header('Location: '.Env::get('URL_BASE').'/dashboard');
                exit;
            }
        }
        $authController->showLoginForm();
        break;

    // Halaman dashboard yang dilindungi
    case 'dashboard':
        
        if ($authResponse['error']) {
            header('Location: '.Env::get('URL_BASE').'/login');
            exit;
        }
        $protectedController = new ProtectedController();
        $protectedController->protectedEndpoint();
        break;

    // Halaman register
    case 'register':
        // if (isset($_SESSION['token'])) {
        //     header('Location: '.Env::get('URL_BASE').'/dashboard');
        //     exit;
        // }

        $authController = new AuthController($db);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = $authController->register($_POST);
            if ($response['error']) {
                $errors = $response['message'];
            } else {
                $successMessage = $response['message'];
                $token = $response['token'];
            }
        }
        require_once __DIR__ . '/src/views/auth/register.php';
        break;

    case 'customers':
        if ($authResponse['error']) {
            header('Location: '.Env::get('URL_BASE').'/login');
            exit;
        }
        $customerController = new CustomerController($db);
        $page = $_GET['page'] ?? 1; // Halaman keberapa
        $search = $_GET['search'] ?? ''; // Pencarian customer
        $customerController->index($page, $search);  // Tampilkan daftar customer
        break;

        case 'customer/create':
            if ($authResponse['error']) {
                header('Location: '.Env::get('URL_BASE').'/login');
                exit;
            }
            $customerController = new CustomerController($db);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $response = $customerController->create($_POST);
                if ($response['error']) {
                    $errors = $response['message'];
                } else {
                    $successMessage = $response['message'];
                }
            }
            $content = __DIR__ . '/src/views/customers/create.php'; // Tampilkan form edit
        

            // Pass the order data and load the layout
            require_once __DIR__ . '/src/views/layout.php';
            break;

        // Route di index.php
        case 'customer/update':
            if ($authResponse['error']) {
                header('Location: '.Env::get('URL_BASE').'/login');
                exit;
            }
            $customerController = new CustomerController($db);
            $id = $_GET['id'] ?? null; // Ambil ID customer
            if ($id) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Proses update customer
                    $response = $customerController->update($id, $_POST);
                    if ($response['error']) {
                        $errors = $response['message'];
                    } else {
                        $_SESSION['message'] = $response['message']; // Set message untuk ditampilkan
                        header('Location: ' . Env::get('URL_BASE') . '/customers');
                        exit;
                    }
                } else {
                    // Tampilkan form untuk edit customer
                    $customerController->edit($id);
                }
            }
            break;


        case 'customer/delete':
            if ($authResponse['error']) {
                header('Location: '.Env::get('URL_BASE').'/login');
                exit;
            }
            $id = $_GET['id'] ?? null;
            if ($id) {
                $customerController = new CustomerController($db);
                $response = $customerController->delete($id); // Delete customer
                if ($response['error']) {
                    $errors = $response['message']; // Show error message
                } else {
                    $successMessage = $response['message']; // Show success message
                }
            }
            break;
            
        case 'customer/detail':
            if ($authResponse['error']) {
                header('Location: '.Env::get('URL_BASE').'/login');
                exit;
            }
            $id = $_GET['id'] ?? null;
            if ($id) {
                $customerController = new CustomerController($db);
                $customerController->detail($id);  // Menampilkan detail customer berdasarkan ID
            } else {
                echo "Customer ID is required.";
            }
            break;
            

    case 'orders':
        if ($authResponse['error']) {
            header('Location: '.Env::get('URL_BASE').'/login');
            exit;
        }
        $orderController = new OrderController($db);
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        $orderController->index($page, $search);
        break;
    
        case 'order/create':
            if ($authResponse['error']) {
                header('Location: '.Env::get('URL_BASE').'/login');
                exit;
            }
            $orderController = new OrderController($db);
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Process the form submission (POST)
                $response = $orderController->create($_POST);
                if ($response['error']) {
                    // If error occurs, pass the error message
                    $errors = $response['message'];
                } else {
                    // If order created successfully, pass the success message
                    $successMessage = $response['message'];
                }
            }
        
            // Fetch all customers for the dropdown and render the create view
            $orderController->showCreateForm($errors ?? null, $successMessage ?? null);
            break;
        
        
        case 'order/detail':
            if ($authResponse['error']) {
                header('Location: '.Env::get('URL_BASE').'/login');
                exit;
            }
            $id = $_GET['id'] ?? null;
            if ($id) {
                $orderController = new OrderController($db);
                $orderController->detail($id); // Show order details
            } else {
                echo "Order ID is required.";
            }
            break;

         // Route di index.php
         case 'order/update':
            if ($authResponse['error']) {
                header('Location: '.Env::get('URL_BASE').'/login');
                exit;
            }
            $orderController = new OrderController($db);
            $id = $_GET['id'] ?? null; // Ambil ID customer
            if ($id) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Proses update customer
                    $response = $orderController->update($id, $_POST);
                    if ($response['error']) {
                        $errors = $response['message'];
                    } else {
                        $_SESSION['message'] = $response['message']; // Set message untuk ditampilkan
                        header('Location: ' . Env::get('URL_BASE') . '/orders');
                        exit;
                    }
                } else {
                    // Tampilkan form untuk edit customer
                    $orderController->edit($id);
                }
            }
            break;
        
        case 'order/delete':
            if ($authResponse['error']) {
                header('Location: '.Env::get('URL_BASE').'/login');
                exit;
            }
            $id = $_GET['id'] ?? null;
            if ($id) {
                $orderController = new OrderController($db);
                $response = $orderController->delete($id);
                if ($response['error']) {
                    echo $response['message'];
                }
            }
            break;
            

    case 'logout':
        if ($authResponse['error']) {
            header('Location: '.Env::get('URL_BASE').'/login');
            exit;
        }
        session_unset();
        session_destroy();
        header('Location: '.Env::get('URL_BASE').'/login');
        exit;

    default:
        http_response_code(404);
        echo "404 - Page Not Found";
        break;
}
?>
