<?php
require_once __DIR__ . '/functions.php';
?>
<nav class="navbar">
    <div class="nav-left">
        <a href="<?php echo BASE_URL; ?>/public/index.php" class="brand">Nice-Tees</a>
    </div>
    <div class="nav-right">
        <a href="<?php echo BASE_URL; ?>/public/index.php">Home</a>
        <a href="<?php echo BASE_URL; ?>/public/cart.php">Cart (<?php echo array_sum($_SESSION['cart'] ?? []); ?>)</a>
        <a href="<?php echo BASE_URL; ?>/public/search.php">Search</a>

        <?php if (isLoggedIn()): ?>
            <span class="nav-text">Hi, <?php echo clean($_SESSION['user_name']); ?></span>
            <?php if (isAdmin()): ?>
                <a href="<?php echo BASE_URL; ?>/admin/index.php">Admin</a>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>/public/logout.php">Logout</a>
        <?php else: ?>
            <a href="<?php echo BASE_URL; ?>/public/login.php">Login</a>
            <a href="<?php echo BASE_URL; ?>/public/register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>
