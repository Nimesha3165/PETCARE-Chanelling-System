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

$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $day=$_POST['day'];
    $start=$_POST['start'];
    $end=$_POST['end'];
    $max=(int)$_POST['max_slots'];
    if(!$day||!$start||!$end) $errors[]='All fields required';
    if(empty($errors)){
        $stmt=$conn->prepare("INSERT INTO vet_schedules(vet_id,day_of_week,start_time,end_time,max_slots) VALUES(?,?,?,?,?)");
        $stmt->bind_param('isssi',$vet_id,$day,$start,$end,$max);
        if(!$stmt->execute()) $errors[]='Failed';
    }
}

$schedules=$conn->query("SELECT * FROM vet_schedules WHERE vet_id=$vet_id ORDER BY FIELD(day_of_week,'Mon','Tue','Wed','Thu','Fri','Sat','Sun')")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Vet Schedule</title>
<style>
    body{font-family:system-ui;background:#f6f9fc;margin:0;color:#222}
    header{background:#4f46e5;color:#fff;padding:20px 40px;display:flex;justify-content:space-between}
    .container{max-width:900px;margin:20px auto;padding:0 20px}
    .card{background:#fff;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06);padding:20px;margin-bottom:20px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #eee;text-align:left}
    label{display:block;margin:12px 0 6px;font-weight:600}
    input,select{width:100%;padding:10px;border:1px solid #ddd;border-radius:8px}
    .btn{display:inline-block;padding:8px 12px;border-radius:8px;background:#4f46e5;color:#fff;text-decoration:none;border:none;cursor:pointer}
    .error{background:#fee2e2;color:#b91c1c;padding:10px;border-radius:8px;margin-bottom:10px}
</style>
</head>
<body>
<header>
    <div>Manage Schedule</div>
    <nav><a href="vet_dashboard.php" style="color:#fff;">Back</a></nav>
</header>
<div class="container">
    <div class="card">
        <h2>Add New Slot</h2>
        <?php foreach($errors as $e): ?><div class="error"><?=e($e)?></div><?php endforeach; ?>
        <form method="post">
            <label>Day</label>
            <select name="day" required>
                <option value="Mon">Mon</option><option value="Tue">Tue</option><option value="Wed">Wed</option>
                <option value="Thu">Thu</option><option value="Fri">Fri</option><option value="Sat">Sat</option><option value="Sun">Sun</option>
            </select>
            <label>Start</label>
            <input type="time" name="start" required>
            <label>End</label>
            <input type="time" name="end" required>
            <label>Max Slots</label>
            <input type="number" name="max_slots" value="10">
            <button class="btn">Save</button>
        </form>
    </div>
    <div class="card">
        <h2>My Schedules</h2>
        <table>
            <thead><tr><th>Day</th><th>Start</th><th>End</th><th>Max</th></tr></thead>
            <tbody>
                <?php foreach($schedules as $s): ?>
                    <tr>
                        <td><?=$s['day_of_week']?></td>
                        <td><?=$s['start_time']?></td>
                        <td><?=$s['end_time']?></td>
                        <td><?=$s['max_slots']?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>