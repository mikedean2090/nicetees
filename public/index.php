<?php
require_once __DIR__ . '/../includes/functions.php';

$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<h1>Welcome to Nice-Tees</h1>
<p>Shop our latest collection of T-shirts.</p>

<div class="product-grid">
    <?php foreach ($products as $product): ?>
        <div class="product-card">
            <a href="product.php?id=<?php echo (int)$product['id']; ?>">
                <img src="<?php echo BASE_URL; ?>/assets/img/<?php echo clean($product['image'] ?: 'placeholder.jpg'); ?>" alt="<?php echo clean($product['name']); ?>">
            </a>
            <h3><?php echo clean($product['name']); ?></h3>
            <p class="price">$<?php echo number_format((float)$product['price'], 2); ?></p>

            <form method="post" action="cart.php">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                <input type="number" name="quantity" value="1" min="1">
                <button type="submit">Add to Cart</button>
            </form>

            <?php if (!isLoggedIn()): ?>
                <p class="hint">Login required to add items.</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
