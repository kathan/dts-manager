<?php
require_once("includes/database.php");
if(isset($_REQUEST['sql'])){
	$res = db_query($_REQUEST['sql']);
	if(db_error()){
		echo db_error();
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