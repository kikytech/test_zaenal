<div class="container ">
    <h3>Order Detail</h3>
    <p><strong>Order Number:</strong> <?= htmlspecialchars($order['order_number']) ?></p>
    <p><strong>Customer Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
    <p><strong>Total Amount:</strong> <?= htmlspecialchars($order['total_amount']) ?></p>
    <p><strong>Order Date:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>

    <!-- Back Button with Link -->
    <a href="<?= Env::get('URL_BASE') ?>/orders" class="btn btn-secondary">Back to Orders</a>
</div>