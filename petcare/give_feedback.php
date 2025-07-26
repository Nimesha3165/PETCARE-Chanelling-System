<?php
require_once 'db.php';
require_once 'functions.php';
require_role('owner');
$user = current_user();
$id = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT a.*, v.id as vid FROM appointments a JOIN vets v ON v.id=a.vet_id WHERE a.id=? AND a.owner_id=? AND a.status='completed'");
$stmt->bind_param('ii',$id,$user['id']);
$stmt->execute();
$app = $stmt->get_result()->fetch_assoc();
if(!$app) die('Not allowed');

$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    if($rating<1 || $rating>5) $errors[]='Invalid rating';
    if(empty($errors)){
        $stmt=$conn->prepare("INSERT INTO feedback(appointment_id,owner_id,vet_id,rating,comment) VALUES(?,?,?,?,?)");
        $stmt->bind_param('iiiis',$id,$user['id'],$app['vet_id'],$rating,$comment);
        if($stmt->execute()){
            // update vet rating
            $conn->query("UPDATE vets v SET avg_rating=(SELECT ROUND(AVG(rating),2) FROM feedback WHERE vet_id=v.id), total_reviews=(SELECT COUNT(*) FROM feedback WHERE vet_id=v.id) WHERE v.id=".$app['vet_id']);
            redirect('my_appointments.php');
        }else{
            $errors[]='Failed';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Feedback</title>
<style>
    body{font-family:system-ui;background:#f6f9fc;margin:0}
    .container{max-width:600px;margin:40px auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06)}
    h1{color:#4f46e5}
    label{display:block;margin:12px 0 6px;font-weight:600}
    select,textarea{width:100%;padding:10px;border:1px solid #ddd;border-radius:8px}
    .btn{background:#4f46e5;color:#fff;border:none;padding:12px 18px;border-radius:8px;cursor:pointer;margin-top:12px}
    .error{background:#fee2e2;color:#b91c1c;padding:10px;border-radius:8px;margin-bottom:10px}
</style>
</head>
<body>
<div class="container">
    <h1>Give Feedback</h1>
    <?php foreach($errors as $e): ?><div class="error"><?=e($e)?></div><?php endforeach; ?>
    <form method="post">
        <label>Rating</label>
        <select name="rating">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <label>Comment</label>
        <textarea name="comment"></textarea>
        <button class="btn">Submit</button>
    </form>
</div>
</body>
</html>