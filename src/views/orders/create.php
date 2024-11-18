<div class="container">
        <h3>Create New Order</h3>

        <!-- Display success or error message -->
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($successMessage) ?>
            </div>
        <?php elseif (isset($errors)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($errors) ?>
            </div>
        <?php endif; ?>

        <!-- Form for creating an order -->
        <form method="POST" action="<?= Env::get('URL_BASE') ?>/order/create">
            <div class="mb-3">
                <label for="order_number" class="form-label">Order Number</label>
                <input type="text" class="form-control" id="order_number" name="order_number" required>
            </div>

            <div class="mb-3">
                <label for="customer_id" class="form-label">Customer</label>
                <select class="form-control" id="customer_id" name="customer_id" required>
                    <option value="">Select Customer</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?= $customer['id'] ?>"><?= htmlspecialchars($customer['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="total_amount" class="form-label">Total Amount</label>
                <input type="number" class="form-control" id="total_amount" name="total_amount" required>
            </div>

            <div class="mb-3">
                <label for="order_date" class="form-label">Order Date</label>
                <input type="date" class="form-control" id="order_date" name="order_date">
            </div>

            <button type="submit" class="btn btn-primary">Create Order</button>
            <a href="<?= Env::get('URL_BASE') ?>/orders" class="btn btn-secondary">Back to Orders</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>