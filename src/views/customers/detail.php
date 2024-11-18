<div class="container">
        <h3>Customer Detail</h3>
        
        <!-- Menampilkan detail customer -->
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td><?= htmlspecialchars($customer['id']) ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?= htmlspecialchars($customer['name']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($customer['email']) ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?= htmlspecialchars($customer['phone']) ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?= htmlspecialchars($customer['address']) ?></td>
            </tr>
            <tr>
                <th>Created At</th>
                <td><?= htmlspecialchars($customer['created_at']) ?></td>
            </tr>
            <tr>
                <th>Updated At</th>
                <td><?= htmlspecialchars($customer['updated_at']) ?></td>
            </tr>
        </table>

        <!-- Tautan untuk kembali ke daftar customer -->
        <a href="<?= Env::get('URL_BASE') ?>/customers" class="btn btn-secondary">Back to Customer List</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>