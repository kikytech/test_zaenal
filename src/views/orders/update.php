<div class="container">
        <h3>Edit Order</h3>

        <!-- Display success or error message -->
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($successMessage) ?>
            </div>
        <?php elseif (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($errors) ?>
            </div>
        <?php endif; ?>

        <!-- Form for editing order -->
        <form method="POST" action="<?= Env::get('URL_BASE') ?>/order/update?id=<?= $order['id'] ?>">
    <div class="mb-3">
        <label for="order_number" class="form-label">Order Number</label>
        <input type="text" class="form-control" id="order_number" name="order_number" value="<?= htmlspecialchars($order['order_number']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="customer_id" class="form-label">Customer</label>
        <select class="form-control" id="customer_id" name="customer_id" required>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer['id'] ?>" <?= $order['customer_id'] == $customer['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($customer['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="total_amount" class="form-label">Total Amount</label>
        <input type="number" class="form-control" id="total_amount" name="total_amount" value="<?= htmlspecialchars($order['total_amount']) ?>" required>
    </div>

    <!-- Order Date field -->
    <div class="mb-3">
        <label for="order_date" class="form-label">Order Date</label>
        <input type="date" class="form-control" id="order_date" name="order_date" value="<?= htmlspecialchars($order['order_date']) ?>" required>

    </div>

    

    <button type="submit" class="btn btn-primary">Update Order</button>
</form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>