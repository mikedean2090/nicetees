<?php
require_once __DIR__ . '/../includes/functions.php';

$query = trim($_GET['q'] ?? '');
$products = [];

if ($query !== '') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
    $like = '%' . $query . '%';
    $stmt->execute([$like, $like]);
    $products = $stmt->fetchAll();
}

include __DIR__ . '/../includes/header.php';
?>

<h1>Search Products</h1>

<form method="get" class="form-inline">
    <input type="text" name="q" placeholder="Search tees..." value="<?php echo clean($query); ?>">
    <button type="submit">Search</button>
</form>

<?php if ($query !== ''): ?>
    <h2>Results for "<?php echo clean($query); ?>"</h2>
    <?php if (empty($products)): ?>
        <p>No products found.</p>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <a href="product.php?id=<?php echo (int)$product['id']; ?>">
                        <img src="<?php echo BASE_URL; ?>/assets/img/<?php echo clean($product['image'] ?: 'placeholder.jpg'); ?>" alt="<?php echo clean($product['name']); ?>">
                    </a>
                    <h3><?php echo clean($product['name']); ?></h3>
                    <p class="price">$<?php echo number_format((float)$product['price'], 2); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
