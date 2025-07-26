<?php
require_once 'db.php';
require_once 'functions.php';
require_role('vet');
$user=current_user();

$stmt=$conn->prepare("SELECT * FROM vets WHERE user_id=?");
$stmt->bind_param('i',$user['id']);
$stmt->execute();
$vet=$stmt->get_result()->fetch_assoc();
$vet_id=$vet['id'];

if(isset($_GET['action']) && isset($_GET['id'])){
    $id=(int)$_GET['id'];
    $action=$_GET['action'];
    if(in_array($action,['confirm','reject','complete'])){
        $status = $action==='confirm'?'confirmed':($action==='reject'?'rejected':'completed');
        $stmt=$conn->prepare("UPDATE appointments SET status=? WHERE id=? AND vet_id=?");
        $stmt->bind_param('sii',$status,$id,$vet_id);
        $stmt->execute();
    }
}

$sql="SELECT a.*, u.name AS owner_name, p.name AS pet_name FROM appointments a
    JOIN users u ON u.id=a.owner_id JOIN pets p ON p.id=a.pet_id WHERE a.vet_id=$vet_id ORDER BY a.created_at DESC";
$apps=$conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Vet Appointments</title>
<style>
    body{font-family:system-ui;background:#f6f9fc;margin:0;color:#222}
    header{background:#4f46e5;color:#fff;padding:20px 40px;display:flex;justify-content:space-between}
    .container{max-width:1100px;margin:20px auto;padding:0 20px}
    table{width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,.06)}
    th,td{padding:12px;border-bottom:1px solid #eee;text-align:left}
    a.btn{display:inline-block;padding:6px 10px;border-radius:6px;background:#4f46e5;color:#fff;text-decoration:none;font-size:14px;margin-right:4px}
</style>
</head>
<body>
<header>
    <div>Vet Appointments</div>
    <nav><a href="vet_dashboard.php" style="color:#fff;">Back</a></nav>
</header>
<div class="container">
    <table>
        <thead>
            <tr><th>Owner</th><th>Pet</th><th>Date</th><th>Time</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach($apps as $a): ?>
                <tr>
                    <td><?=e($a['owner_name'])?></td>
                    <td><?=e($a['pet_name'])?></td>
                    <td><?=e($a['schedule_date'])?></td>
                    <td><?=e($a['schedule_time'])?></td>
                    <td><?=e($a['status'])?></td>
                    <td>
                        <?php if($a['status']==='pending'): ?>
                            <a class="btn" href="?action=confirm&id=<?=$a['id']?>">Confirm</a>
                            <a class="btn" href="?action=reject&id=<?=$a['id']?>" style="background:#dc2626">Reject</a>
                        <?php elseif($a['status']==='confirmed'): ?>
                            <a class="btn" href="?action=complete&id=<?=$a['id']?>" style="background:#16a34a">Complete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>