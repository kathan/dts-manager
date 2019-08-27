<?php
require_once('includes/app.php');
require_once('DbTable.php');
$path_info_ary = explode('/', $_SERVER['PATH_INFO']);
$path_ary = [];
foreach($path_info_ary as $path_info){
    if($path_info !== ''){
        $path_ary[] = $path_info;
    }
}
$object = $path_ary[0];
$table = new DbTable(App::$db, $object);
$binds = [];
if(isset($path_ary[1])){
    $id = $path_ary[1];
    $binds[$object.'_id'] = $id;
}
if(isset($_GET)){
    foreach($_GET as $key => $val){
        $binds[$key] = $val;
    }
    $result = $table->query(['*'], $binds, [0, 20]);
    header('Content-Type: application/json');
    echo json_encode([$object.'s' => $result->fetchAll(PDO::FETCH_ASSOC)], JSON_PRETTY_PRINT);
}
