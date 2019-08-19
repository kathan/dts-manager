<?php

	require_once"includes/global.php";
	require_once"includes/auth.php";
	
	
	require_once"includes/warehouse_table.php";
	$l = new warehouse_table();
	echo $l->render();

?>