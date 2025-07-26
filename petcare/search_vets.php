<?php
require_once 'db.php';
require_once 'functions.php';
require_role('owner');

$q = trim($_GET['q'] ?? '');
$sql = "SELECT v.*, u.name, u.email, u.phone FROM vets v JOIN users u ON u.id=v.user_id WHERE v.approved=1";
$params = [];
if($q !== ''){
    $sql .= " AND (u.name LIKE ? OR v.specialty LIKE ?)";
}
$stmt = $conn->prepare($sql);
if($q !== ''){
    $like = "%$q%";
    $stmt->bind_param('ss', $like, $like);
}
$stmt->execute();
$vets = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Find Vets</title>
<style>
    body{font-family:system-ui;background:#f6f9fc;margin:0;color:#222}
    header{background:#4f46e5;color:#fff;padding:20px 40px;display:flex;justify-content:space-between}
    .container{max-width:1000px;margin:20px auto;padding:0 20px}
    .card{background:#fff;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06);padding:20px;margin-bottom:20px}
    .vet{display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #eee;padding:10px 0}
    .btn{display:inline-block;padding:8px 12px;border-radius:8px;background:#4f46e5;color:#fff;text-decoration:none}
    input{padding:10px;border:1px solid #ddd;border-radius:8px;width:100%}
    form{margin-bottom:20px}
</style>
</head>
<body>
<header>
    <div>Search Vets</div>
    <nav><a href="owner_dashboard.php" style="color:#fff;">Back</a></nav>
</header>
<div class="container">
    <div class="card">
        <form method="get">
            <input type="text" name="q" placeholder="Search by name or specialty" value="<?=e($q)?>">
        </form>
        <?php foreach($vets as $v): ?>
            <div class="vet">
                <div>
                    <h3 style="margin:0;color:#4f46e5;"><?=e($v['name'])?></h3>
                    <div>Specialty: <?=e($v['specialty'])?> | Fee: LKR <?=e($v['fee'])?></div>
                    <small>Rating: <?=e($v['avg_rating'])?> (<?=e($v['total_reviews'])?>)</small>
                </div>
                <a class="btn" href="book_appointment.php?vet_id=<?=$v['id']?>">Book</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>