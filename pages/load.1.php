<?php

	require_once"includes/global.php";
	require_once"includes/auth.php";
	
	
	require_once"includes/load_table.php";
	$l = new load_table();
	echo $l->render();

?>