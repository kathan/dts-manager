<?php
date_default_timezone_set('America/Chicago');
require_once('includes/app.php');
require_once('includes/DB.php');
ini_set('display_errors', 'On');//Debug only
require_once('global.php');
require_once('includes/load_table.php');
$lt = new load_table();
if(isset($_POST['load_id']) && isset($_POST['action'])){
	
	$lt->add();
	//echo $lt->last_id;
	header('Location: /?page=load&load_id='.$_POST['load_id']);
}
?>
<form method="post">
<input type="hidden" name="action" value="<?=$lt->new_str?>"/><br/>
Load ID:<input type="text" name="load_id" value="<?=$_POST['load_id']?>"/><br/>
<input type="submit" value="Create" />
</form>