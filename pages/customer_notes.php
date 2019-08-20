<?php

	require_once("includes/global.php");
	require_once("includes/auth.php");
	require_once("includes/customer_notes_table.php");
	$l = new customer_notes_table();
	echo $l->render();

?>