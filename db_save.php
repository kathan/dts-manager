<?php
date_default_timezone_set('America/Chicago');
ini_set('display_errors', 'On');//Debug only
require_once('includes/app.php');
require_once('includes/global.php');
//require_once('DB_Table.php');
require_once('includes/auth.php');
if(logged_in()){
	
	require_once"includes/dts_table.php";
	
	$t = new dts_table($_REQUEST['table']);
	//logError("debug", "db_save.php");
	switch(get_action()){
		case $t->update:
			echo $t->update();
			
			break;
		case $t->delete:
			echo $t->delete();
			break;
		default:
			if($_REQUEST['table'] == 'load_carrier')
			{
				//clear_load_carrier($_POST['load_id']);
				set_post('booked_with', get_user_id());
			}
			echo $t->add();
			break;
	}
	logError($t->sql, "db_save.php");
	if($t->error_str){
		echo $t->error_str;
		logError($t->error_str."\n".$t->sql, "db_save.php");
	}
}

function clear_load_carrier($load_id){
	
	$sql = "DELETE FROM load_carrier WHERE load_id = $load_id";
	DB::query($sql);
}
?>