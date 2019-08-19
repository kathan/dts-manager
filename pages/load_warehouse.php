<?php
	require_once"includes/global.php";
	require_once"includes/auth.php";
	
	require_once"includes/load_warehouse_table.php";
	$l = new load_warehouse_table();
	echo $l->render();
?>