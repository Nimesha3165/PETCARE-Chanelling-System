<?php
require_once 'db.php';
require_once 'functions.php';
require_role('vet');
$user = current_user();

// Find my vet row
$stmt = $conn->prepare("SELECT * FROM vets WHERE user_id=?");
$stmt->bind_param('i',$user['id']);
$stmt->execute();
$vet = $stmt->get_result()->fetch_assoc();
if(!$vet){
    echo "Vet profile not found"; exit;
}
?>
<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Vet Dashboard</title>
<style>
    body{font-family:system-ui;background:#f6f9fc;margin:0;color:#222}
    header{background:#4f46e5;color:#fff;padding:20px 40px;display:flex;justify-content:space-between}
    .container{max-width:1100px;margin:20px auto;padding:0 20px}
    .card{background:#fff;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06);padding:20px;margin-bottom:20px}
    .btn{display:inline-block;padding:8px 12px;border-radius:8px;background:#4f46e5;color:#fff;text-decoration:none}
</style>
</head>
<body>
<header>
    <div>Vet Dashboard</div>
    <nav><a href="logout.php" style="color:#fff;">Logout</a></nav>
</header>
<div class="container">
    <div class="card">
        <h2>My Profile (Approval: <?= $vet['approved'] ? 'Approved' : 'Pending' ?>)</h2>
        <p>License: <?=e($vet['license_no'])?> | Specialty: <?=e($vet['specialty'])?> | Fee: LKR <?=e($vet['fee'])?></p>
        <p>Rating: <?=e($vet['avg_rating'])?> (<?=e($vet['total_reviews'])?>)</p>
    </div>
    <div class="card">
        <h2>Schedules</h2>
        <a class="btn" href="vet_schedule.php">Manage Schedules</a>
    </div>
    <div class="card">
        <h2>Appointments</h2>
        <a class="btn" href="vet_appointments.php">View Appointments</a>
    </div>
</div>
</body>
</html>