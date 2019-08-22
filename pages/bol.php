<?php
	require_once("Template.php");
	require_once("DB.php");

$sql = "SELECT	l.load_id,
						l.class,
						l.commodity,
						l.trailer_type,
						lc.cell_number,
						lc.driver_name,
						lc.tractor_number,
						lc.trailer_number,
						c.carrier_id,
						origin_name,
						origin_address,
						origin_state,
						origin_city,
						origin_zip,
						origin_phone,
						origin_fax,
						origin_notes,
						origin_contact,
						pickup_time,
						dest_name,
						dest_address,
						dest_state,
						dest_city,
						dest_zip,
						dest_phone,
						dest_fax,
						dest_notes,
						dest_contact,
						delivery_date,
						delivery_time,
						pick_num,
						dest_num,
						ltl_number,
						l.ltl_carrier,
						l.weight,
						l.size,
						l.pallets,
						l.carrier_line_haul,
						l.pro_number,
						carrier_line_haul_amount line_haul_amount,
						carrier_detention,
						carrier_detention_amount detention_amount,
						carrier_tonu,
						carrier_tonu_amount tonu_amount,
						carrier_unload_load,
						carrier_unload_load_amount unload_load_amount,
						carrier_fuel,
						carrier_fuel_amount fuel_amount,
						carrier_other,
						carrier_other_amount other_amount,
						c.contact_name,
						c.name carrier_name,
						c.main_phone_number carrier_phone,
						c.fax carrier_fax,
						(SELECT CONCAT(u.first_name, ' ', u.last_name) FROM `users` u WHERE u.user_id=lc.booked_with) booked_name,
						(SELECT username FROM `users` WHERE user_id=lc.booked_with) booked_with,
						(SELECT username FROM `users`, customer c WHERE user_id = c.acct_owner AND c.customer_id = l.customer_id) booked_salesperson,
						pickup_date,
						c.phys_address carrier_address
				FROM `load` l, carrier c,  load_carrier lc,
				(SELECT lwp.load_id,
						name origin_name,
						address origin_address,
						state origin_state,
						city origin_city,
						zip origin_zip,
						phone origin_phone,
						fax origin_fax,
						notes origin_notes,
						contact_name origin_contact,
						pick_dest_num pick_num,
						DATE_FORMAT(lwp.activity_date, '%c/%e/%Y') pickup_date,
						CONCAT(DATE_FORMAT(lwp.open_time, '%h:%i %p'), ' - ', DATE_FORMAT(lwp.close_time, '%h:%i %p')) pickup_time 
					FROM warehouse w, load_warehouse lwp
					WHERE lwp.type ='PICK'
					AND w.warehouse_id = lwp.warehouse_id) origin, 
				(SELECT lwd.load_id,
						name dest_name,
						address dest_address,
						state dest_state,
						city dest_city,
						zip dest_zip,
						phone dest_phone,
						fax dest_fax,
						notes dest_notes,
						contact_name dest_contact,
						pick_dest_num dest_num,
						DATE_FORMAT(lwd.activity_date, '%c/%e/%Y') delivery_date,
						CONCAT(DATE_FORMAT(lwd.open_time, '%h:%i %p'), ' - ', DATE_FORMAT(lwd.close_time, '%h:%i %p')) delivery_time 
					FROM warehouse w, load_warehouse lwd
					WHERE lwd.type ='DEST' 
					AND w.warehouse_id = lwd.warehouse_id) dest
				WHERE l.load_id = ?
				AND lc.load_id = l.load_id
				AND c.carrier_id = lc.carrier_id
				AND origin.load_id = l.load_id
				AND dest.load_id = l.load_id";
$binds = [$_REQUEST['load_id']];
$re = DB::query($sql, $binds);
$t = new Template();
$load = DB::to_array($re, true);
$t->assign('load', $load);
echo $t->fetch(App::getTempDir().'bol.tpl');

?>