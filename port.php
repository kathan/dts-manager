<?php
require_once('include/App.php');

switch($_GET['action'])
{
	case 'getLTLCarriers':
		$sql = "SELECT *
				FROM ltl_carriers
				WHERE active = 1";
		$re
		break;
}
?>