<?php
require_once __DIR__ . '/../includes/functions.php';

if (!isAdmin()) {
    redirect('login.php');
}

$action = $_GET['action'] ?? '';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = (float)($_POST['price'] ?? 0);
    $category    = trim($_POST['category'] ?? '');
    $image       = trim($_POST['image'] ?? '');

    if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image, category)
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $image, $category]);
        redirect('products.php');
    } elseif ($action === 'edit' && $id > 0) {
        $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, image=?, category=? WHERE id=?");
        $stmt->execute([$name, $description, $price, $image, $category, $id]);
        redirect('products.php');
    }
}

if ($action === 'delete' && $id > 0) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$id]);
    redirect('products.php');
}

include __DIR__ . '/../includes/header.php';
?>

<h1>Manage Products</h1>

<?php if ($action === 'create' || ($action === 'edit' && $id > 0)): ?>

    <?php
    $product = ['name' => '', 'description' => '', 'price' => '', 'image' => '', 'category' => ''];
    if ($action === 'edit') {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        if (!$product) {
            die("Product not found.");
        }
    }
    ?>

    <h2><?php echo $action === 'create' ? "Add Product" : "Edit Product"; ?></h2>
    <form method="post" class="form">
        <label>Name</label>
        <input type="text" name="name" value="<?php echo clean($product['name']); ?>" required>

        <label>Description</label>
        <textarea name="description"><?php echo clean($product['description']); ?></textarea>

        <label>Price</label>
        <input type="number" step="0.01" name="price" value="<?php echo clean($product['price']); ?>" required>

        <label>Image filename (inside assets/img)</label>
        <input type="text" name="image" value="<?php echo clean($product['image']); ?>" placeholder="white-tee.jpg">

        <label>Category</label>
        <input type="text" name="category" value="<?php echo clean($product['category']); ?>">

        <button type="submit" class="btn-primary">Save</button>
        <a href="products.php">Cancel</a>
    </form>

<?php else: ?>

    <p><a href="products.php?action=create" class="btn-primary">Add New Product</a></p>

    <?php
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    $products = $stmt->fetchAll();
    ?>

    <table class="table">
        <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Price</th><th>Category</th><th>Image</th><th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?php echo (int)$p['id']; ?></td>
                <td><?php echo clean($p['name']); ?></td>
                <td>$<?php echo number_format((float)$p['price'], 2); ?></td>
                <td><?php echo clean($p['category']); ?></td>
                <td>
                    <img style="height:50px" src="<?php echo BASE_URL; ?>/assets/img/<?php echo clean($p['image'] ?: 'placeholder.jpg'); ?>" alt="">
                </td>
                <td>
                    <a href="products.php?action=edit&id=<?php echo (int)$p['id']; ?>">Edit</a> |
                    <a href="products.php?action=delete&id=<?php echo (int)$p['id']; ?>" onclick="return confirm('Delete this product?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
