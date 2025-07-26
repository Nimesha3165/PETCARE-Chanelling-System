<?php
require_once 'db.php';
require_once 'functions.php';
require_role('owner');
$user = current_user();
$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = trim($_POST['name']);
    $species = $_POST['species'];
    $breed = trim($_POST['breed']);
    $dob = $_POST['dob'] ?: null;
    $notes = trim($_POST['notes']);
    if($name==='') $errors[]='Name required';
    if(empty($errors)){
        $stmt=$conn->prepare("INSERT INTO pets(owner_id,name,species,breed,dob,notes) VALUES(?,?,?,?,?,?)");
        $stmt->bind_param('isssss',$user['id'],$name,$species,$breed,$dob,$notes);
        if($stmt->execute()) redirect('owner_dashboard.php');
        else $errors[]='Failed to add pet';
    }
}
?>
<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Add Pet</title>
<style>
    body{font-family:system-ui;background:#f6f9fc;margin:0}
    .container{max-width:600px;margin:40px auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06)}
    h1{color:#4f46e5}
    label{display:block;margin:12px 0 6px;font-weight:600}
    input,select,textarea{width:100%;padding:10px;border:1px solid #ddd;border-radius:8px}
    .btn{background:#4f46e5;color:#fff;border:none;padding:12px 18px;border-radius:8px;cursor:pointer;margin-top:12px}
    .error{background:#fee2e2;color:#b91c1c;padding:10px;border-radius:8px;margin-bottom:10px}
</style>
</head>
<body>
<div class="container">
    <h1>Add Pet</h1>
    <?php foreach($errors as $e): ?><div class="error"><?=e($e)?></div><?php endforeach; ?>
    <form method="post">
        <label>Name</label>
        <input type="text" name="name" required>
        <label>Species</label>
        <select name="species">
            <option value="dog">Dog</option>
            <option value="cat">Cat</option>
            <option value="bird">Bird</option>
            <option value="other">Other</option>
        </select>
        <label>Breed</label>
        <input type="text" name="breed">
        <label>DOB</label>
        <input type="date" name="dob">
        <label>Notes</label>
        <textarea name="notes"></textarea>
        <button class="btn">Save</button>
    </form>
</div>
</body>
</html>