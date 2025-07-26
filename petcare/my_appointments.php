<?php
require_once 'db.php';
require_once 'functions.php';
require_role('owner');
$user = current_user();

$stmt=$conn->prepare("SELECT a.*, p.name AS pet_name, u.name AS vet_name FROM appointments a
    JOIN pets p ON p.id=a.pet_id
    JOIN vets v ON v.id=a.vet_id
    JOIN users u ON u.id=v.user_id
    WHERE a.owner_id=? ORDER BY a.created_at DESC");
$stmt->bind_param('i',$user['id']);
$stmt->execute();
$apps=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>My Appointments</title>
<style>
    body{font-family:system-ui;background:#f6f9fc;margin:0;color:#222}
    header{background:#4f46e5;color:#fff;padding:20px 40px;display:flex;justify-content:space-between}
    .container{max-width:1000px;margin:20px auto;padding:0 20px}
    table{width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,.06)}
    th,td{padding:12px;border-bottom:1px solid #eee;text-align:left}
    .btn{display:inline-block;padding:6px 10px;border-radius:6px;background:#4f46e5;color:#fff;text-decoration:none;font-size:14px}
    .ok{background:#dcfce7;color:#166534;padding:10px;border-radius:8px;margin-bottom:10px}
</style>
</head>
<body>
<header>
    <div>My Appointments</div>
    <nav><a href="owner_dashboard.php" style="color:#fff;">Back</a></nav>
</header>
<div class="container">
    <?php if(isset($_GET['booked'])): ?><div class="ok">Appointment booked!</div><?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>Pet</th><th>Vet</th><th>Date</th><th>Time</th><th>Status</th><th>Payment</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($apps as $a): ?>
            <tr>
                <td><?=e($a['pet_name'])?></td>
                <td><?=e($a['vet_name'])?></td>
                <td><?=e($a['schedule_date'])?></td>
                <td><?=e($a['schedule_time'])?></td>
                <td><?=e($a['status'])?></td>
                <td><?=e($a['payment_status'])?></td>
                <td>
                    <?php if($a['status']==='completed' && $a['payment_status']==='paid'): ?>
                        <a class="btn" href="give_feedback.php?id=<?=$a['id']?>">Feedback</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>