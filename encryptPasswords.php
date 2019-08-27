<?php
require_once('./includes/app.php');
require_once('DbTable.php');
require_once('auth.php');

$userTable = new DbTable(App::$db, 'users');

$result = $userTable->query(['*'], ['active' => 1]);
while($r = $result->fetch(PDO::FETCH_ASSOC)){
    $encryptedPassword = Auth::encryptPassword($r['password']);
    $userTable->update(['hash_password' => $encryptedPassword], ['user_id' => $r['user_id']]);
    echo "Encrypted user $r[user_id]";
}
