<div class="container">
        <h3>Customer List</h3>

        <!-- Button Add -->
        <a href="<?= Env::get('URL_BASE') ?>/customer/create" class="btn btn-success mb-3">Add New Customer</a>
        
        <?php
            // Tampilkan pesan dari session jika ada
            if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['message']) ?>
                </div>
                <?php unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
            endif;
        ?>

        <!-- Form Search -->
       <!-- Form Search -->
        <form method="GET" action="<?= Env::get('URL_BASE') ?>/customers">
            <div class="input-group mb-3">
                <!-- Menggunakan isset() untuk memeriksa apakah 'search' ada dalam $_GET -->
                <input type="text" class="form-control" placeholder="Search by username or email" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"> 
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>


        <!-- Tampilkan daftar customer -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= htmlspecialchars($customer['id']) ?></td>
                        <td><?= htmlspecialchars($customer['name']) ?></td>
                        <td><?= htmlspecialchars($customer['email']) ?></td>
                        <td>
                            <!-- Tombol Detail -->
                            <a href="<?= Env::get('URL_BASE') ?>/customer/detail?id=<?= $customer['id'] ?>" class="btn btn-info">Detail</a>
                            
                            <!-- Tombol Update -->
                            <a href="<?= Env::get('URL_BASE') ?>/customer/update?id=<?= $customer['id'] ?>" class="btn btn-warning">Edit</a>
                            
                            <!-- Tombol Delete -->
                            <a href="<?= Env::get('URL_BASE') ?>/customer/delete?id=<?= $customer['id'] ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                </li>
                <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>