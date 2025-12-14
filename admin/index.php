<?php
require_once __DIR__ . '/../includes/functions.php';

if (!isAdmin()) {
    redirect('login.php');
}

$productCount = $pdo->query("SELECT COUNT(*) AS c FROM products")->fetch()['c'];
$orderCount   = $pdo->query("SELECT COUNT(*) AS c FROM orders")->fetch()['c'];

include __DIR__ . '/../includes/header.php';
?>

<h1>Admin Dashboard</h1>

<p>Total Products: <?php echo (int)$productCount; ?></p>
<p>Total Orders: <?php echo (int)$orderCount; ?></p>

<p>
    <a href="products.php" class="btn-primary">Manage Products</a>
    <a href="orders.php" class="btn-primary">View Orders</a>
    <a href="logout.php" class="btn-primary">Logout</a>
</p>

<?php include __DIR__ . '/../includes/footer.php'; ?>
