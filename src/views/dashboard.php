<?php

// Jika tidak ada token, arahkan ke halaman login
if (!isset($_SESSION['token'])) {
    header("Location: " . Env::get('BASE_URL') . "/login");
    exit;
}

// Tampung data user dari controller yang diteruskan
$user = isset($user) ? $user : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h3 class="card-title text-center">Welcome to your Dashboard</h3>

                        <?php if ($user): ?>
                            <p><strong>Username:</strong> <?= htmlspecialchars($user->name) ?></p>
                            <p><strong>Role:</strong> <?= htmlspecialchars($user->role) ?></p>
                            <p><strong>User ID:</strong> <?= htmlspecialchars($user->sub) ?></p>
                        <?php else: ?>
                            <p>Error: User data not found!</p>
                        <?php endif; ?>

                        <!-- Link Logout -->
                        <a href="<?= Env::get('URL_BASE') ?>/logout" class="btn btn-danger w-100">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
