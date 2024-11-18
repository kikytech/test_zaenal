<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Customer.php';

class OrderController {
    private $orderModel;
    private $customerModel;

    public function __construct($db) {
        $this->orderModel = new Order($db);
        $this->customerModel = new Customer($db); // To check customer relationships
    }

    // Display all orders with pagination and search
    public function index($page = 1, $search = '') {
        $perPage = 10;
        $orders = $this->orderModel->getAllOrders($page, $perPage, $search);
        $totalOrders = $this->orderModel->getOrderCount($search);
        $totalPages = ceil($totalOrders / $perPage);
        // Set the content for this page
        $content = __DIR__ . '/../views/orders/index.php';

        // Pass the order data and load the layout
        require_once __DIR__ . '/../views/layout.php';
        // Pass orders and customer data to the view
        //require_once __DIR__ . '/../views/orders/index.php'; 
    }

    // Show order details
    public function detail($id) {
        $order = $this->orderModel->getOrderById($id);
        if (!$order) {
            echo "Order not found.";
            exit;
        }

        // Pass the order data to the view
        $content = __DIR__ . '/../views/orders/detail.php';  // Ensure the path is correct

        // Pass the order data and load the layout
        require_once __DIR__ . '/../views/layout.php';
    }


    // Getter for customerModel
    public function getCustomerModel() {
        return $this->customerModel;
    }

    // Show the Create Order form
    public function showCreateForm($errors = null, $successMessage = null) {
        // Fetch all customers using the getter method
        $customers = $this->getCustomerModel()->getAllCustomersNoPage(); // Access customerModel through the getter
        
        $content = __DIR__ . '/../views/orders/create.php'; // Render the create form
        

        // Pass the order data and load the layout
        require_once __DIR__ . '/../views/layout.php';
    }

    // Create a new order
    public function create($data) {
        // Check if required fields are present
        if (empty($data['order_number']) || empty($data['customer_id']) || empty($data['total_amount'])) {
            return ['error' => true, 'message' => 'Order number, customer, and total amount are required.'];
        }

        // Check if status exists in the $data array
        if (!isset($data['status'])) {
            $data['status'] = 'pending'; // Set a default value if 'status' is not provided
        }

        try {
            // Call the model to create the order
            $this->orderModel->createOrder($data['order_number'], $data['customer_id'], $data['total_amount'], $data['order_date'], $data['status']);
            return ['error' => false, 'message' => 'Order created successfully.'];
        } catch (Exception $e) {
            // Return the error message if something goes wrong
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    // Menampilkan form untuk edit order
    public function edit($id) {
        // Fetch the order details
        $order = $this->orderModel->getOrderById($id);

        // Fetch the list of customers, passing necessary arguments (e.g., page 1, no search)
        $page = 1;  // default page
        $perPage = 10;  // default per page
        $search = '';  // default empty search
        $customers = $this->customerModel->getAllCustomers($page, $perPage, $search); 

        // If the order doesn't exist, show an error message
        if (!$order) {
            echo "Order not found.";
            exit;
        }
        $content = __DIR__ . '/../views/orders/update.php'; // Render the update form
        

        // Pass the order data and load the layout
        require_once __DIR__ . '/../views/layout.php';
    }

    


    // Update order method in Controller
    public function update($id, $data) {
        // Make sure the order_date is in the format YYYY-MM-DD
        $order_date = $data['order_date']; // Assuming the date is fetched from the database

        // Convert the date to YYYY-MM-DD if it's in a different format
        if (strpos($order_date, '-') === false) {  // If the date is not in 'YYYY-MM-DD' format
            $order_date = date('Y-m-d', strtotime($order_date));
        }
        // Validate the input data
        if (empty($data['order_number']) || empty($data['customer_id']) || empty($data['total_amount'])) {
            return ['error' => true, 'message' => 'Order number, customer, and total amount are required.'];
        }

        // Call the updateOrder method in the Order model
        $result = $this->orderModel->updateOrder($id, $data['order_number'], $data['customer_id'], $data['total_amount'], $data['order_date']);

        if ($result) {
            return ['error' => false, 'message' => 'Order updated successfully.'];
        } else {
            return ['error' => true, 'message' => 'Failed to update order.'];
        }
    }



    // Delete an order
    public function delete($id) {
        if (!$id) {
            return ['error' => true, 'message' => 'Order ID is required.'];
        }

        try {
            $this->orderModel->deleteOrder($id);
             // Redirect ke halaman daftar customer dengan pesan sukses
             $_SESSION['message'] = 'Orders deleted successfully.';
             header('Location: '.Env::get('URL_BASE').'/orders');  // Arahkan ke halaman daftar customer setelah delete
             exit;
        } catch (Exception $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}
?>
