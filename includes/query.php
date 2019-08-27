<?php
require_once("includes/database.php");
if(isset($_REQUEST['sql'])){
	$res = App::$db->query($_REQUEST['sql']);
	if($res->errorCode() > 0){
		echo $res->errorCode();
	}else{
	}
}

function sql_form(){
	echo "<form method='post'>
			<textarea name='sql' cols=80 rows=20>$_REQUEST[sql]</textarea>
			<input type='submit' value='Run'>
			</form>";
	
}
?>