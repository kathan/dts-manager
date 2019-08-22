<?php
require_once('auth.php');
function get_overdue(){
	$sql = "SELECT load_id
				, activity_date
				,(select activity_date from `load` where load_id = lw.load_id) load_date
				,type
				,(select name from warehouse where warehouse_id = lw.warehouse_id) name
				,(select city from warehouse where warehouse_id = lw.warehouse_id) city
				,(select state from warehouse where warehouse_id = lw.warehouse_id) state
				,(select username from `users` where user_id = (select acct_owner from customer where customer_id = (select customer_id from `load` where load_id = lw.load_id))) acct_owner
				,(select username from `users` where user_id = (select order_by from `load` where load_id = lw.load_id)) order_by
				,'Overdue' urgency
				, CONCAT(activity_date, ' ' , IFNULL(close_time, CASE DAYNAME(activity_date)
				WHEN 'Sunday' THEN (select sun_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Monday' THEN (select mon_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Tuesday' THEN (select tues_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Wednesday' THEN (select wed_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Thursday' THEN (select thurs_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Friday' THEN (select fri_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Saturday' THEN (select sat_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				ELSE 'N/A'
			END) ) closes_at
			FROM load_warehouse lw
			WHERE CONCAT(lw.activity_date, ' ', IFNULL(close_time, CASE DAYNAME(activity_date)
				WHEN 'Sunday' THEN (select sun_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Monday' THEN (select mon_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Tuesday' THEN (select tues_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Wednesday' THEN (select wed_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Thursday' THEN (select thurs_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Friday' THEN (select fri_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Saturday' THEN (select sat_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				ELSE 'N/A'
			END) ) < now()
			AND (complete != 1 or complete is null)
			AND load_id in (SELECT load_id 
							FROM `load`
							WHERE (cancelled !=1 or cancelled is null)
							AND (order_by = ?
								OR customer_id in (SELECT customer_id
													FROM customer
													WHERE acct_owner = ?) 
								)
							)";
	$binds = [Auth::getUserId(), Auth::getUserId()];
	
	$re = DB::query($sql, $binds);
	echo DB::error();
	$ary=[];
	while($r = DB::fetch_assoc($re)){
		$ary[]=$r;
	}
	return $ary;
}

function get_approaching(){
	$sql = "SELECT load_id
				, activity_date
				,(select activity_date from `load` where load_id = lw.load_id) load_date
				,type
				,(select name from warehouse where warehouse_id = lw.warehouse_id) name
				,(select city from warehouse where warehouse_id = lw.warehouse_id) city
				,(select state from warehouse where warehouse_id = lw.warehouse_id) state
				,(select username from `users` where user_id = (select acct_owner from customer where customer_id = (select customer_id from `load` where load_id = lw.load_id))) acct_owner
				,(select username from `users` where user_id = (select order_by from `load` where load_id = lw.load_id)) order_by
				,'Approaching' urgency
				, CONCAT(activity_date, ' ' , IFNULL(close_time, CASE DAYNAME(activity_date)
				WHEN 'Sunday' THEN (select sun_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Monday' THEN (select mon_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Tuesday' THEN (select tues_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Wednesday' THEN (select wed_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Thursday' THEN (select thurs_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Friday' THEN (select fri_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Saturday' THEN (select sat_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				ELSE 'N/A'
			END) ) closes_at
			FROM load_warehouse lw
			WHERE CONCAT(lw.activity_date, ' ', IFNULL(close_time, CASE DAYNAME(activity_date)
				WHEN 'Sunday' THEN (select sun_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Monday' THEN (select mon_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Tuesday' THEN (select tues_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Wednesday' THEN (select wed_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Thursday' THEN (select thurs_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Friday' THEN (select fri_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				WHEN 'Saturday' THEN (select sat_close_time FROM warehouse where warehouse_id = lw.warehouse_id)
				ELSE 'N/A'
			END) ) < ADDTIME(now(), '1:00:00')
			AND CONCAT(lw.activity_date, ' ', close_time) > now()
			AND (complete != 1 or complete is null)
			AND load_id in (SELECT load_id 
							FROM `load`
							WHERE (cancelled != 1 or cancelled is null)
							AND (order_by = ?
								OR customer_id in (SELECT customer_id
													FROM customer
													WHERE acct_owner = ?) 
								)
							)";
	
	$binds = [Auth::getUserId(), Auth::getUserId()];

	$re = DB::query($sql, $binds);
	echo DB::error();
	$ary=[];
	while($r = DB::fetch_assoc($re)){
		$ary[]=$r;
	}
	return $ary;
}
?>