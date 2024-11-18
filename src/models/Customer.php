<?php
class Customer {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Mendapatkan semua customer dengan pagination dan search
    public function getAllCustomers($page, $perPage, $search = '') {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM customers WHERE name LIKE :search OR email LIKE :search LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':search', "%$search%");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mendapatkan jumlah total customer untuk pagination
    public function getCustomerCount($search = '') {
        $sql = "SELECT COUNT(*) FROM customers WHERE name LIKE :search OR email LIKE :search";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':search', "%$search%");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Mendapatkan customer berdasarkan ID
    public function getCustomerById($id) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    // Cek apakah email sudah ada
    public function isEmailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM customers WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;  // Mengembalikan true jika email sudah ada
    }

    // Menambahkan customer baru
    public function createCustomer($name, $email, $phone = null, $address = null) {
        // Cek apakah email sudah ada
        if ($this->isEmailExists($email)) {
            throw new Exception("Email $email is already registered."); // Throw exception jika email sudah ada
        }

        // Jika email belum ada, lanjutkan dengan insert data customer
        $stmt = $this->db->prepare("INSERT INTO customers (name, email, phone, address) VALUES (:name, :email, :phone, :address)");
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':address', $address);
        return $stmt->execute();
    }

    // Mengupdate data customer
    public function updateCustomer($id, $name, $email, $phone = null, $address = null) {
        $stmt = $this->db->prepare("UPDATE customers SET name = :name, email = :email, phone = :phone, address = :address WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':address', $address);
        return $stmt->execute();
    }

    // Delete customer by ID
    public function deleteCustomer($id) {
        $query = "DELETE FROM customers WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }


    // Mendapatkan semua pesanan yang dimiliki pelanggan
    public function getOrders() {
        $query = "SELECT * FROM orders WHERE customer_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengembalikan array pesanan yang dimiliki customer
    }

    // Fetch all customers
    public function getAllCustomersNoPage() {
        $query = "SELECT * FROM customers";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
