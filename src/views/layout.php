<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
    <body>
        <div class="container">

            <div class="row">
                <div class="col-md-3 mt-4">
                    <div class="containers">
                        <h5>Menu</h5>
                        <ul class="list-group">
                            <li class="list-group-item"><a href="<?= Env::get('URL_BASE') ?>/dashboard">Dashboard</a></li>
                            <li class="list-group-item"><a href="<?= Env::get('URL_BASE') ?>/customers">Customer Management</a></li>
                            <li class="list-group-item"><a href="<?= Env::get('URL_BASE') ?>/orders">Order Management</a></li>
                            <li class="list-group-item"><a href="<?= Env::get('URL_BASE') ?>/register">User Register</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-9 mt-4">
                    <?php require_once $content; ?>  <!-- This is where specific page content will be inserted -->
                </div>    
            <div>
        </div>
    </body>
</html>
