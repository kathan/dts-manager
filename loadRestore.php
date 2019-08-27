<?php
date_default_timezone_set('America/Chicago');
require_once('includes/app.php');
require_once('includes/DB_Table.php');
$sql = "SELECT *
FROM `load_restore`
WHERE field != ''
ORDER BY `load_restore`.`time_entered` ASC";
$t = new DB_Table('load');
/*foreach(array_keys($t->columns) as $key){
	echo $key.'<br>';
}*/
$re = App::$db->query($sql);
while($r = $re->fetch(PDO::FETCH_ASSOC)){
	$a = Array('load_id'=>$r['load_id'],
			$r['field']=>$r['value']);
	if($l = loadExists($r['load_id'])){
		if(strtotime($r['time_entered']) < strtotime($l['activity_date'])){
			$a['activity_date'] = $r['time_entered'];
			$a['order_by'] = $r['user'];
		}
		
		$t->update($a);
	}else{
		$t->insert($a);
	}
	if($t->error_str){
		echo $t->error_str.' '.json_encode($a).' '.$t->sql.'<br>';
		break;
		//file_put_contents('./log/restore.log',  $t->error_str.' '.json_encode($a).' '.$t->sql."\n", FILE_APPEND);
	}
	flush();
}

function loadExists($load_id){
	$sql = "SELECT *
			FROM `load`
			WHERE load_id = $load_id";
	$re = App::$db->query($sql);
	if($r = $re->fetch(PDO::FETCH_ASSOC)){
		return $r;
	}else{
		return false;
	}
}
?>