<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Fungsi untuk memanipulasi nomor telepon
        function validatePhoneNumber(event) {
            var phoneField = document.getElementById('phone');
            var phoneNumber = phoneField.value;

            // Hapus karakter non-digit (selain angka)
            phoneNumber = phoneNumber.replace(/\D/g, '');

            // Batasi nomor telepon menjadi maksimal 15 digit
            if (phoneNumber.length > 13) {
                phoneNumber = phoneNumber.substring(0, 13);
            }

            // Set nilai kembali ke input
            phoneField.value = phoneNumber;
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h3>Create New Customer</h3>

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

        <!-- Form for creating a customer -->
        <form method="POST" action="<?= Env::get('URL_BASE') ?>/customer/create">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" oninput="validatePhoneNumber(event)" required>
                <small class="form-text text-muted">Only numeric values (max 15 digits).</small>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create Customer</button>
            <a href="<?= Env::get('URL_BASE') ?>/customers" class="btn btn-secondary">Back to Customer List</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
