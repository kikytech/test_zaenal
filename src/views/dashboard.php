<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
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