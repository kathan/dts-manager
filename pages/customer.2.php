<?php

	require_once"includes/global.php";
	require_once"includes/auth.php";
	
	
	require_once"includes/customer_table.php";
	$l = new customer_table();
	echo $l->render();

?>