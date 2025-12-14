<?php
require_once __DIR__ . '/../includes/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_admin = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id']   = (int)$user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['is_admin']  = 1;
        redirect('index.php');
    } else {
        $errors[] = "Invalid admin credentials.";
    }
}

include __DIR__ . '/../includes/header.php';
?>

<h1>Admin Login</h1>

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

<?php include __DIR__ . '/../includes/footer.php'; ?>
