<?php
require_once 'db.php';
require_once 'functions.php';
require_role('owner');
$user = current_user();

// Load pets
$stmt = $conn->prepare("SELECT * FROM pets WHERE owner_id=?");
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$pets = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Owner Dashboard</title>
<style>
    body{font-family:system-ui;background:#f6f9fc;margin:0;color:#222}
    header{background:#4f46e5;color:#fff;padding:20px 40px;display:flex;justify-content:space-between}
    a{color:#4f46e5;text-decoration:none}
    .container{max-width:1100px;margin:20px auto;padding:0 20px}
    .card{background:#fff;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06);padding:20px;margin-bottom:20px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #eee;text-align:left}
    .btn{display:inline-block;padding:8px 12px;border-radius:8px;background:#4f46e5;color:#fff}
</style>
</head>
<body>
<header>
    <div>Owner Dashboard</div>
    <nav>
        <a href="index.php" style="color:#fff;margin-right:10px;">Home</a>
        <a href="logout.php" style="color:#fff;">Logout</a>
    </nav>
</header>
<div class="container">
    <div class="card">
        <h2>ඔබේ සුරතලා</h2>
        <a class="btn" href="add_pet.php">+ Add Pet</a>
        <table>
            <thead><tr><th>Name</th><th>Species</th><th>Breed</th><th>DOB</th></tr></thead>
            <tbody>
                <?php foreach($pets as $p): ?>
                <tr>
                    <td><?=e($p['name'])?></td>
                    <td><?=e($p['species'])?></td>
                    <td><?=e($p['breed'])?></td>
                    <td><?=e($p['dob'])?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="card">
        <h2>Book a Vet</h2>
        <a class="btn" href="search_vets.php">Search Vets</a>
    </div>
    <div class="card">
        <h2>My Appointments</h2>
        <a class="btn" href="my_appointments.php">View</a>
    </div>
</div>
</body>
</html>