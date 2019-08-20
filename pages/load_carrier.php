<?php
	require_once"includes/global.php";
	require_once"includes/auth.php";
	if(logged_in()){
		require_once"includes/load_carrier_table.php";
		$l = new load_carrier_table();
		echo $l->render();
	}
?>	