<?php

	require_once"includes/global.php";
	require_once"includes/auth.php";
	
	
	require_once"includes/carrier_table.php";
	$l = new carrier_table();
	echo $l->render();

?>