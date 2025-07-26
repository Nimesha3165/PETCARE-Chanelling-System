<?php
require_once 'db.php';
require_once 'functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND is_active=1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($user = $res->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            if ($user['role'] === 'owner') redirect('owner_dashboard.php');
            if ($user['role'] === 'vet') redirect('vet_dashboard.php');
            if ($user['role'] === 'admin') redirect('admin_dashboard.php');
        } else {
            $errors[] = 'Invalid credentials';
        }
    } else {
        $errors[] = 'User not found or inactive';
    }
}
?>
<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Login</title>
<style>
    body{font-family:system-ui;background:#f6f9fc;margin:0}
    .container{max-width:400px;margin:60px auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06)}
    h1{color:#4f46e5}
    label{display:block;margin:12px 0 6px;font-weight:600}
    input{width:100%;padding:10px;border:1px solid #ddd;border-radius:8px}
    .btn{background:#4f46e5;color:#fff;border:none;padding:12px 18px;border-radius:8px;cursor:pointer;margin-top:12px}
    .error{background:#fee2e2;color:#b91c1c;padding:10px;border-radius:8px;margin-bottom:10px}
    .ok{background:#dcfce7;color:#166534;padding:10px;border-radius:8px;margin-bottom:10px}
</style>
</head>
<body>
<div class="container">
    <h1>Login</h1>
    <?php if(isset($_GET['registered'])): ?><div class="ok">Registration success! Please wait admin approval if you registered as Vet.</div><?php endif; ?>
    <?php foreach($errors as $e): ?><div class="error"><?=e($e)?></div><?php endforeach; ?>
    <form method="post">
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button class="btn">Login</button>
    </form>
    <p>No account? <a href="register.php">Register</a></p>
</div>
</body>
</html>