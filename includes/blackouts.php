<?php
require_once"includes/portal.php";
$sql ="SELECT * FROM events WHERE event_date >= NOW()";
if(isset($_REQUEST['created_by']))
{
	$sql .= "WHERE created_by = $_REQUEST[created_by]";
}
$p = new portal($sql);
$p->render();
?>