<?php
require_once __DIR__ . '/../includes/functions.php';

requireLoginOrRedirect();

$items = getCartItems($pdo);
$total = getCartTotal($pdo);

if (empty($items)) {
    redirect('cart.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $address   = trim($_POST['address'] ?? '');
    $city      = trim($_POST['city'] ?? '');
    $state     = trim($_POST['state'] ?? '');
    $zip       = trim($_POST['zip'] ?? '');

    if ($full_name === '') $errors[] = "Full name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if ($address === '') $errors[] = "Address is required.";

    if (empty($errors)) {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("
                INSERT INTO orders (user_id, full_name, email, address, city, state, zip, total)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $user_id = $_SESSION['user_id'];
            $stmt->execute([$user_id, $full_name, $email, $address, $city, $state, $zip, $total]);
            $order_id = $pdo->lastInsertId();

            $stmtItem = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ");

            foreach ($items as $item) {
                $p   = $item['product'];
                $qty = (int)$item['quantity'];
                $stmtItem->execute([$order_id, (int)$p['id'], $qty, (float)$p['price']);
            }

            $pdo->commit();
            clearCart();
            redirect("checkout.php?success=1");
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Error placing order: " . $e->getMessage();
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<h1>Checkout</h1>

<?php if (isset($_GET['success'])): ?>
    <p>Thank you! Your order has been placed.</p>
<?php else: ?>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $err): ?>
                <p><?php echo clean($err); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <h3>Order Total: $<?php echo number_format((float)$total, 2); ?></h3>

    <form method="post" class="form">
        <label>Full Name</label>
        <input type="text" name="full_name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Address</label>
        <textarea name="address" required></textarea>

        <label>City</label>
        <input type="text" name="city" required>

        <label>State</label>
        <input type="text" name="state" required>

        <label>ZIP</label>
        <input type="text" name="zip" required>

        <button type="submit" class="btn-primary">Place Order</button>
    </form>

<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
