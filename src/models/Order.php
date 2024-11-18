<?php
class Order {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Create a new order
    public function createOrder($order_number, $customer_id, $total_amount, $order_date, $status) {
        $query = "INSERT INTO orders (order_number, customer_id, total_amount, order_date, status) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$order_number, $customer_id, $total_amount, $order_date, $status]);
    }

    public function updateOrder($id, $orderNumber, $customerId, $totalAmount, $orderDate) {
        try {
            $sql = "UPDATE orders SET order_number = :order_number, customer_id = :customer_id, total_amount = :total_amount, order_date = :order_date WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':order_number', $orderNumber, PDO::PARAM_STR);
            $stmt->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
            $stmt->bindValue(':total_amount', $totalAmount, PDO::PARAM_STR);
            $stmt->bindValue(':order_date', $orderDate, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception('Error updating order');
            }
        } catch (Exception $e) {
            // Log error for debugging
            error_log("Error updating order: " . $e->getMessage());
            return false;
        }
    }
    
    
    
    // Delete order
    public function deleteOrder($id) {
        $query = "DELETE FROM orders WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
    }

    // Fetch all orders with optional search and pagination
    public function getAllOrders($page, $perPage, $search) {
        $offset = ($page - 1) * $perPage;
        $search = "%" . $search . "%";  // For partial matching

        // Fetch orders with customer details
        $stmt = $this->db->prepare(
            "SELECT o.id, o.order_number, o.total_amount, o.order_date, o.status, c.name as customer_name
             FROM orders o
             JOIN customers c ON o.customer_id = c.id
             WHERE o.order_number LIKE :search OR c.name LIKE :search
             ORDER BY o.order_date DESC
             LIMIT :perPage OFFSET :offset"
        );
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count the total number of orders
    public function getOrderCount($search) {
        $search = "%" . $search . "%";
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM orders o
             JOIN customers c ON o.customer_id = c.id
             WHERE o.order_number LIKE :search OR c.name LIKE :search"
        );
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    // Get an order by its ID along with customer details
    public function getOrderById($id) {
        $query = "
            SELECT o.*, c.name AS customer_name, c.email AS customer_email 
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.id
            WHERE o.id = :id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        return $order ? $order : null;
    }

    
}
?>
