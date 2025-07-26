<?php
require_once 'db.php';
require_once 'functions.php';
require_role('admin');
?>
<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<style>
    body{font-family:system-ui;background:#f6f9fc;margin:0;color:#222}
    header{background:#4f46e5;color:#fff;padding:20px 40px;display:flex;justify-content:space-between}
    .container{max-width:900px;margin:20px auto;padding:0 20px}
    .card{background:#fff;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06);padding:20px;margin-bottom:20px}
    .btn{display:inline-block;padding:8px 12px;border-radius:8px;background:#4f46e5;color:#fff;text-decoration:none}
</style>
</head>
<body>
<header>
    <div>Admin Dashboard</div>
    <nav><a href="logout.php" style="color:#fff;">Logout</a></nav>
</header>
<div class="container">
    <div class="card">
        <h2>Users</h2>
        <a class="btn" href="admin_manage_users.php">Manage Users</a>
    </div>
    <div class="card">
        <h2>Vets</h2>
        <a class="btn" href="admin_manage_vets.php">Manage Vets</a>
    </div>
    <div class="card">
        <h2>Appointments</h2>
        <a class="btn" href="admin_manage_appointments.php">Manage Appointments</a>
    </div>
</div>
</body>
</html>