<?php
require_once __DIR__ . '/../includes/functions.php';

// Remove via GET to avoid nested forms
if (isset($_GET['remove'])) {
    requireLoginOrRedirect();
    removeFromCart((int)$_GET['remove']);
    redirect('cart.php');
}

// If user is trying to add something, require login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    requireLoginOrRedirect();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        addToCart($_POST['product_id'], $_POST['quantity'] ?? 1);
        redirect('cart.php');
    } elseif ($action === 'update') {
        requireLoginOrRedirect();
        if (isset($_POST['qty']) && is_array($_POST['qty'])) {
            foreach ($_POST['qty'] as $pid => $qty) {
                updateCartItem($pid, $qty);
            }
        }
        redirect('cart.php');
    }
}

$items = getCartItems($pdo);
$total = getCartTotal($pdo);

include __DIR__ . '/../includes/header.php';
?>

<h1>Your Cart</h1>

<?php if (!isLoggedIn()): ?>
    <div class="errors">
        <p>You must log in to use the cart.</p>
        <p><a class="btn-primary" href="<?php echo BASE_URL; ?>/public/login.php?return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Login</a></p>
    </div>
<?php endif; ?>

<?php if (empty($items)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <form method="post">
        <input type="hidden" name="action" value="update">
        <table class="table">
            <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <?php $p = $item['product']; ?>
                <tr>
                    <td><?php echo clean($p['name']); ?></td>
                    <td>$<?php echo number_format((float)$p['price'], 2); ?></td>
                    <td>
                        <input type="number" name="qty[<?php echo (int)$p['id']; ?>]" value="<?php echo (int)$item['quantity']; ?>" min="0">
                    </td>
                    <td>$<?php echo number_format((float)$item['subtotal'], 2); ?></td>
                    <td>
                        <a class="btn-primary" href="cart.php?remove=<?php echo (int)$p['id']; ?>">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit">Update Cart</button>
    </form>

    <h3>Total: $<?php echo number_format((float)$total, 2); ?></h3>

    <a href="checkout.php" class="btn-primary">Proceed to Checkout</a>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
