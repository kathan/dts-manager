<?php

$portals = Array('load_carrier' => "	SELECT c.carrier_id, c.name, driver_name, tractor_number, trailer_number, cell_number, equipment_type,
										(SELECT username FROM `users` WHERE user_id = lc.booked_with) booked_with
										FROM load_carrier lc, carrier c
										WHERE c.carrier_id = lc.carrier_id",
				'customer_notes' => "	SELECT c.carrier_id, c.name, driver_name, tractor_number, trailer_number, cell_number, equipment_type,
										(SELECT username FROM `users` WHERE user_id = lc.booked_with) booked_with
										FROM load_carrier lc, carrier c
										WHERE c.carrier_id = lc.carrier_id");
if(isset($_REQUEST['table'])){
	require_once("includes/portal.php");
	$t = new portal($portals[$_REQUEST['table']]);
	$t->set_table($_REQUEST['table']);
	$t->set_primary_key('carrier_id');
	echo $t->render();
}
