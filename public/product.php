<?php
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Product not found.");
}

include __DIR__ . '/../includes/header.php';
?>

<div class="product-detail">
    <img src="<?php echo BASE_URL; ?>/assets/img/<?php echo clean($product['image'] ?: 'placeholder.jpg'); ?>" alt="<?php echo clean($product['name']); ?>">
    <div>
        <h2><?php echo clean($product['name']); ?></h2>
        <p class="price">$<?php echo number_format((float)$product['price'], 2); ?></p>
        <p><?php echo nl2br(clean($product['description'])); ?></p>

        <form method="post" action="cart.php">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
            <label>Qty:</label>
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit">Add to Cart</button>
        </form>

        <?php if (!isLoggedIn()): ?>
            <p class="hint">You must login to add items to cart.</p>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
