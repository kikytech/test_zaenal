<?php
$dsn = "pgsql:host=127.0.0.1;port=5433;dbname=dataku;";
$username = "postgres";
$password = "YoungRich2025";

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
