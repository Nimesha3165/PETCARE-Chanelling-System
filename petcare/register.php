<?php
require_once 'db.php';
require_once 'functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'owner';

    if ($name === '' || $email === '' || $password === '') {
        $errors[] = 'All fields are required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'Email already in use.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users(name,email,password,role,phone) VALUES (?,?,?,?,?)");
            $stmt->bind_param('sssss', $name, $email, $hash, $role, $phone);
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                if ($role === 'vet') {
                    // Create vet record pending approval
                    $license_no = $_POST['license_no'] ?? '';
                    $specialty = $_POST['specialty'] ?? '';
                    $fee = $_POST['fee'] ?? 0;
                    $about = $_POST['about'] ?? '';
                    $stmt2 = $conn->prepare("INSERT INTO vets(user_id, license_no, specialty, about, fee) VALUES (?,?,?,?,?)");
                    $stmt2->bind_param('isssd', $user_id, $license_no, $specialty, $about, $fee);
                    $stmt2->execute();
                }
                redirect('login.php?registered=1');
            } else {
                $errors[] = 'Registration failed. Try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Register</title>
<style>
    body{font-family:system-ui;background:#f6f9fc;margin:0}
    .container{max-width:600px;margin:40px auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06)}
    h1{color:#4f46e5}
    label{display:block;margin:12px 0 6px;font-weight:600}
    input,select,textarea{width:100%;padding:10px;border:1px solid #ddd;border-radius:8px}
    .role-box{display:flex;gap:10px;margin-bottom:12px}
    .btn{background:#4f46e5;color:#fff;border:none;padding:12px 18px;border-radius:8px;cursor:pointer;margin-top:12px}
    .error{background:#fee2e2;color:#b91c1c;padding:10px;border-radius:8px;margin-bottom:10px}
</style>
</head>
<body>
<div class="container">
    <h1>Register</h1>
    <?php foreach($errors as $e): ?><div class="error"><?=e($e)?></div><?php endforeach; ?>
    <form method="post">
        <label>Name</label>
        <input type="text" name="name" required>
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Phone</label>
        <input type="text" name="phone">
        <label>Password</label>
        <input type="password" name="password" required>
        <label>Role</label>
        <select name="role" id="role" onchange="toggleVet()">
            <option value="owner">Owner</option>
            <option value="vet">Vet</option>
        </select>
        <div id="vetFields" style="display:none;">
            <label>License No</label>
            <input type="text" name="license_no">
            <label>Specialty</label>
            <input type="text" name="specialty" placeholder="Ex: Surgery, Dental">
            <label>Consultation Fee (LKR)</label>
            <input type="number" step="0.01" name="fee">
            <label>About</label>
            <textarea name="about"></textarea>
        </div>
        <button class="btn">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</div>
<script>
function toggleVet(){
    var role = document.getElementById('role').value;
    document.getElementById('vetFields').style.display = role==='vet' ? 'block' : 'none';
}
</script>
</body>
</html>r