<?php
require_once 'db.php';
require_once 'functions.php';
require_role('admin');

if(isset($_GET['toggle']) && isset($_GET['id'])){
    $id=(int)$_GET['id'];
    $conn->query("UPDATE users SET is_active = IF(is_active=1,0,1) WHERE id=$id AND role!='admin'");