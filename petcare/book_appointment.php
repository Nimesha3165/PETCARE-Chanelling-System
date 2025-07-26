<?php
require_once 'db.php';
require_once 'functions.php';
require_role('owner');
$user = current_user();
$vet_id = (int)($_GET['vet_id'] ?? 0);

// Load Vet
$stmt = $conn->prepare("SELECT v.*, u.name FROM vets v JOIN users u ON u.id=v.user_id WHERE v.id=? AND v.approved=1");
$stmt->bind_param('i', $vet_id);
$stmt->execute();
$vet = $stmt->get_result()->fetch_assoc();
if(!$vet) die('Vet not found or not approved.');

// Load my pets
$stmt = $conn->prepare("SELECT * FROM pets WHERE owner_id=?");
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$pets = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $pet_id = (int)$_POST['pet_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = trim($_POST['reason']);
    if(!$pet_id) $errors[]='Select a pet';
    if(!$date) $errors[]='Select date';
    if(!$time) $errors[]='Select time';
    if(empty($errors)){
        $stmt=$conn->prepare("INSERT INTO appointments(owner_id,pet_id,vet_id,schedule_date,schedule_time,reason) VALUES(?,?,?,?,?,?)");
        $stmt->bind_param('iiisss',$user['id'],$pet_id,$vet_id,$date,$time,$reason);
        if($stmt->execute()){
            redirect('my_appointments.php?booked=1');
        }else{
            $errors[]='Booking failed';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Book Appointment</title>
<style>
    body{font-family:system-ui;background:#f6f9fc;margin:0}
    .container{max-width:700px;margin:40px auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06)}
    h1{color:#4f46e5}
    label{display:block;margin:12px 0 6px;font-weight:600}
    input,select,textarea{width:100%;padding:10px;border:1px solid #ddd;border-radius:8px}
    .btn{background:#4f46e5;color:#fff;border:none;padding:12px 18px;border-radius:8px;cursor:pointer;margin-top:12px}
    .error{background:#fee2e2;color:#b91c1c;padding:10px;border-radius:8px;margin-bottom:10px}
</style>
</head>
<body>
<div class="container">
    <h1>Book: <?=e($vet['name'])?> (LKR <?=e($vet['fee'])?>)</h1>
    <?php foreach($errors as $e): ?><div class="error"><?=e($e)?></div><?php endforeach; ?>
    <form method="post">
        <label>Pet</label>
        <select name="pet_id" required>
            <option value="">-- select --</option>
            <?php foreach($pets as $p): ?>
                <option value="<?=$p['id']?>"><?=$p['name']?> (<?=$p['species']?>)</option>
            <?php endforeach; ?>
        </select>
        <label>Date</label>
        <input type="date" name="date" required>
        <label>Time</label>
        <input type="time" name="time" required>
        <label>Reason / Symptoms</label>
        <textarea name="reason" rows="3"></textarea>
        <button class="btn">Book</button>
    </form>
</div>
</body>
</html>