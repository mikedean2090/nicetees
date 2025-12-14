<?php
require_once __DIR__ . '/../config/config.php';

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1;
}

function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function clean($str): string {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function requireLoginOrRedirect(): void {
    if (!isLoggedIn()) {
        $return = urlencode($_SERVER['REQUEST_URI'] ?? (BASE_URL . '/public/index.php'));
        redirect(BASE_URL . "/public/login.php?return=$return");
    }
}

// CART SESSION INIT
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function addToCart($product_id, $quantity = 1): void {
    $product_id = (int)$product_id;
    $quantity   = max(1, (int)$quantity);

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

function updateCartItem($product_id, $quantity): void {
    $product_id = (int)$product_id;
    $quantity   = (int)$quantity;
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

function removeFromCart($product_id): void {
    $product_id = (int)$product_id;
    unset($_SESSION['cart'][$product_id]);
}

function clearCart(): void {
    $_SESSION['cart'] = [];
}

function getCartItems(PDO $pdo): array {
    $items = [];
    if (empty($_SESSION['cart'])) {
        return $items;
    }

    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    foreach ($products as $product) {
        $pid = (int)$product['id'];
        $qty = (int)($_SESSION['cart'][$pid] ?? 0);
        if ($qty <= 0) continue;

        $items[] = [
            'product'  => $product,
            'quantity' => $qty,
            'subtotal' => $qty * (float)$product['price'],
        ];
    }
    return $items;
}

function getCartTotal(PDO $pdo): float {
    $items = getCartItems($pdo);
    $total = 0.0;
    foreach ($items as $item) {
        $total += (float)$item['subtotal'];
    }
    return $total;
}
