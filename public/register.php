<?php
require_once __DIR__ . '/../includes/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($name === '') $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hash]);

            $_SESSION['user_id']   = (int)$pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            $_SESSION['is_admin']  = 0;

            $return = $_GET['return'] ?? (BASE_URL . '/public/index.php');
            redirect($return);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "Email already registered.";
            } else {
                $errors[] = "Error creating account.";
            }
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<h1>Register</h1>

<?php if (!empty($errors)): ?>
    <div class="errors">
        <?php foreach ($errors as $err): ?>
            <p><?php echo clean($err); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post" class="form">
    <label>Name</label>
    <input type="text" name="name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <label>Confirm Password</label>
    <input type="password" name="confirm" required>

    <button type="submit" class="btn-primary">Create Account</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
