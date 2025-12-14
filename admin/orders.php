<?php
require_once __DIR__ . '/../includes/functions.php';

if (!isAdmin()) {
    redirect('login.php');
}

include __DIR__ . '/../includes/header.php';

$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
?>

<h1>Orders</h1>

<table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Email</th>
        <th>Total</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($orders as $order): ?>
        <tr>
            <td><?php echo (int)$order['id']; ?></td>
            <td><?php echo clean($order['full_name']); ?></td>
            <td><?php echo clean($order['email']); ?></td>
            <td>$<?php echo number_format((float)$order['total'], 2); ?></td>
            <td><?php echo clean($order['created_at']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
