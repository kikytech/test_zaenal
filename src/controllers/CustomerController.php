<?php
require_once __DIR__ . '/../models/Customer.php';

class CustomerController {
    private $customerModel;

    public function __construct($db) {
        $this->customerModel = new Customer($db);
    }

    // Menampilkan daftar customer dengan pagination dan search
    public function index($page = 1, $search = '') {
        $perPage = 10;
        $customers = $this->customerModel->getAllCustomers($page, $perPage, $search);
        // Pastikan tidak ada spasi di depan dan belakang string search
        // Menghapus semua spasi di depan dan belakang
        $searchtr = trim($search);
        //print_r($searchtr); die;
        // Mendapatkan jumlah customer berdasarkan pencarian
        $totalCustomers = $this->customerModel->getCustomerCount($searchtr);

        $totalPages = ceil($totalCustomers / $perPage);

        // Tampilkan daftar customer
        $content = __DIR__ . '/../views/customers/index.php'; // Render the update form
        

        // Pass the order data and load the layout
        require_once __DIR__ . '/../views/layout.php';
    }

    // Menampilkan detail customer
    public function detail($id) {
        $customer = $this->customerModel->getCustomerById($id);
        if (!$customer) {
            echo "Customer not found.";
            exit;
        }
        // Tampilkan halaman detail customer
        $content = __DIR__ . '/../views/customers/detail.php'; // Render the update form
        

        // Pass the order data and load the layout
        require_once __DIR__ . '/../views/layout.php';
    }


    public function create($data) {
        // Validasi data yang diterima
        if (empty($data['name']) || empty($data['email'])) {
            return ['error' => true, 'message' => 'Name and email are required.'];
        }
    
        // Sanitasi nomor telepon
        $phone = $this->sanitizePhoneNumber($data['phone']);  // Memformat nomor telepon
    
        try {
            // Memanggil model untuk menyimpan customer baru
            $response = $this->customerModel->createCustomer($data['name'], $data['email'], $phone, $data['address'] ?? null);
            return ['error' => false, 'message' => 'Customer created successfully.'];
        } catch (Exception $e) {
            // Jika terjadi error, misalnya email sudah ada
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
    
    // Fungsi untuk memformat nomor telepon
    private function sanitizePhoneNumber($phone) {
        // Hapus semua karakter non-digit (selain angka)
        $phone = preg_replace('/\D/', '', $phone);

        // Jika nomor telepon diawali dengan 0, hapus angka 0 dan tambahkan +62
        if (substr($phone, 0, 1) === '0') {
            $phone = '+62' . substr($phone, 1);
        } elseif (substr($phone, 0, 1) !== '+') {
            // Jika tidak dimulai dengan +, tambahkan +62
            $phone = '+62' . $phone;
        }

        // Batasi nomor telepon menjadi maksimal 16 karakter (termasuk + dan angka)
        // Jika lebih dari 16 karakter, potong sehingga hanya maksimal 16 karakter yang disimpan
        if (strlen($phone) > 16) {
            $phone = substr($phone, 0, 16);
        }

        return $phone;
    }

    

    // Menampilkan form untuk edit customer
    public function edit($id) {
        $customer = $this->customerModel->getCustomerById($id);
        if (!$customer) {
            echo "Customer not found.";
            exit;
        }
        $content = __DIR__ . '/../views/customers/update.php'; // Tampilkan form edit
        

        // Pass the order data and load the layout
        require_once __DIR__ . '/../views/layout.php';
    }

    // Mengupdate data customer
    public function update($id, $data) {
        if (empty($data['name']) || empty($data['email'])) {
            return ['error' => true, 'message' => 'Name and email are required.'];
        }

        $name = $data['name'];
        $email = $data['email'];
        $phone = $data['phone'] ?? null;
        $address = $data['address'] ?? null;

        // Update customer di database
        $this->customerModel->updateCustomer($id, $name, $email, $phone, $address);
        return ['error' => false, 'message' => 'Customer updated successfully.'];
    }

    // Menghapus customer
    public function delete($id) {
        // Validasi ID customer
        if (!$id) {
            return ['error' => true, 'message' => 'Customer ID is required.'];
        }

        try {
            // Panggil model untuk menghapus customer
            $this->customerModel->deleteCustomer($id);

            // Redirect ke halaman daftar customer dengan pesan sukses
            $_SESSION['message'] = 'Customer deleted successfully.';
            header('Location: '.Env::get('URL_BASE').'/customers');  // Arahkan ke halaman daftar customer setelah delete
            exit;
        } catch (Exception $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }


}
?>
