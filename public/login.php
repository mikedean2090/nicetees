<?php
require_once __DIR__ . '/../includes/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id']   = (int)$user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['is_admin']  = (int)$user['is_admin'];

        $return = $_GET['return'] ?? (BASE_URL . '/public/index.php');
        redirect($return);
    } else {
        $errors[] = "Invalid email or password.";
    }
}

include __DIR__ . '/../includes/header.php';
?>

<h1>Login</h1>

<?php if (!empty($errors)): ?>
    <div class="errors">
        <?php foreach ($errors as $err): ?>
            <p><?php echo clean($err); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post" class="form">
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit" class="btn-primary">Login</button>
</form>

<p>Don't have an account? <a href="register.php<?php echo isset($_GET['return']) ? '?return=' . urlencode($_GET['return']) : ''; ?>">Register here</a>.</p>

<?php include __DIR__ . '/../includes/footer.php'; ?>
