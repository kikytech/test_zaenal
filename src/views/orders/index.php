<div class="container ">
        <h3>Order List</h3>

        <!-- Button Add -->
        <a href="<?= Env::get('URL_BASE') ?>/order/create" class="btn btn-success mb-3">Add New Order</a>
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
        <form method="GET" action="<?= Env::get('URL_BASE') ?>/orders">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search by order number or customer name" name="search" value="<?= htmlspecialchars(trim($_GET['search'] ?? '')) ?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <!-- Display orders -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Order Number</th>
                    <th>Customer Name</th>
                    <th>Total Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['order_number']) ?></td>
                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                        <td><?= htmlspecialchars($order['total_amount']) ?></td>
                        <td>
                            <!-- Detail Button -->
                            <a href="<?= Env::get('URL_BASE') ?>/order/detail?id=<?= $order['id'] ?>" class="btn btn-info">Detail</a>
                            
                            <!-- Edit Button -->
                            <a href="<?= Env::get('URL_BASE') ?>/order/update?id=<?= $order['id'] ?>" class="btn btn-warning">Edit</a>
                            
                            <!-- Delete Button -->
                            <a href="<?= Env::get('URL_BASE') ?>/order/delete?id=<?= $order['id'] ?>" class="btn btn-danger">Delete</a>
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