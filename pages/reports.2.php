<?php
	require_once"/home/dts2828/include/Template.php";
	require_once"/home/dts2828/include/DB.php";
	require_once"includes/global.php";
	require_once"includes/auth.php";
	
if(logged_in_as('admin'))
{
	echo "<title>DTS-Monthly Report</title>";
	
	echo month_report_form();
	if(get_action() == 'Report')
	{
		echo get_report();
	}
}

function month_report_form()
{
	$t = new Template();
	$t->assign('users', get_users());
	//echo $_REQUEST['user_id'];
	$t->assign('user_id', $_REQUEST['user_id']);
	$t->assign('page', $_REQUEST['page']);
	return $t->fetch('/home/dts2828/www/dts/templates/report_form.tpl');
}

function get_users()
{
	$sql = "SELECT *
			FROM users";
	$re = DB::query($sql);
	$ary = Array('');
	while($r = DB::fetch_assoc($re))
	{
		$ary[$r['user_id']] = $r['username'];
	}
	//print_r($ary);
	return $ary;
}

function get_report()
{
	
	$first_day_str = "1-".get_month($_REQUEST['start_Month'])."-".$_REQUEST['start_Year'];
	$first_of_month = date ('Y-m-d', strtotime($first_day_str));
	$start_date = $_REQUEST['start_Year']."-".str_pad($_REQUEST['start_Month'], 2, '0', STR_PAD_LEFT)."-01";
	$start_week_day = "1";
	
	$first_sat_str = "first sat ".get_month($_REQUEST['start_Month'])."-".$_REQUEST['start_Year'];
	$first_mon_str = "first sun ".get_month($_REQUEST['start_Month'])."-".$_REQUEST['start_Year'];
	$end_date = date ('Y-m-d', strtotime($first_sat_str));
	
	$end_week_day = date ('j', strtotime($first_sat_str));
	
	$first_week = date('W', strtotime($first_day_str));
	
	$first_mon_date = date('Y-m-d', strtotime($first_mon_str));
	
	$day_count =date('t', strtotime($first_day_str));
	
	$last_day_week_str = ($day_count)."-".$_REQUEST['start_Month']."-".$_REQUEST['start_Year'];
	$last_day_str = "$day_count-".$_REQUEST['start_Month']."-".$_REQUEST['start_Year'];
	
	$last_of_month = date ('Y-m-d', strtotime($last_day_str));
	$last_week = date('W', strtotime($last_day_week_str));
	$first_week > $last_week ? $last_week = 53 :'' ; 
	$week_count = ($last_week+1)- ($first_week);
	
	$c .= "<input type='button' id='print_button' value='Print' onclick='print();' />";
	$c .= "<img id='logo' src='http://".IMG_ROOT."/dts.gif'>";
	$c .= "<div class='title'>".get_month($_REQUEST['start_Month'])." ".$_REQUEST['start_Year']."</div>";
	$c .= "<table width='100%'>\n";
	
	for($w=1;$w <= $week_count; $w++)
	{
		
		$c .= "<thead class='weekly_head'><tr><td colspan=6>Week of $_REQUEST[start_Month]/$start_week_day thru $_REQUEST[start_Month]/$end_week_day</td></tr>\n";
		
		$c .= "<tr><th>Date</th><th>Load #</th><th>Customer</th><th>Cust Rate</th><th>Carr Rate</th><th>Profit</th></tr></thead>\n";
		
		$c .= get_weekly($start_date, $end_date);
		$c .= get_summary($start_date, $end_date);
		if(date('N', strtotime($start_date." + 7 days")) == 7)
		{
			//echo "not sun";
			$start_week_day = date('j', strtotime($start_date." + 7 days"));
			$start_date = date('Y-m-d', strtotime($start_date." + 7 days"));
		}else{
			$start_week_day = date('j', strtotime($first_mon_date));
			$start_date = date('Y-m-d', strtotime($first_mon_date));
		
		}
		if(strtotime($end_date." + 7 days") > strtotime($last_day_str))
		//If the end of the week is greater than the last day of the month
		{
			$end_week_day = date('j', strtotime($last_day_str));
			$end_date = date('Y-m-d', strtotime($last_day_str));
		}else{
			$end_week_day = date('j', strtotime($end_date." + 7 days"));
			$end_date = date('Y-m-d', strtotime($end_date." + 7 days"));
		}
	}
	$c .= get_summary($first_of_month, $last_of_month);
	$c .= "</table>\n";
	
	return $c;
}

function get_occur($n)
{
	$occ = array("first", "second", "third", "fourth", "fifth");
	return $occ[$n];
}

function get_weekly($mon, $fri)
{
	require_once('includes/view.php');
$sql = "	SELECT DATE_FORMAT(activity_date, '%m/%e') date,
					load_id,
					c.name customer,
					CONCAT('$',FORMAT((cust_line_haul * cust_line_haul_amount) + 
					(cust_detention * cust_detention_amount) + 
					(cust_tonu * cust_tonu_amount) + 
					(cust_unload_load * cust_unload_load_amount) +
					(cust_fuel * cust_fuel_amount) + 
					(cust_other * cust_other_amount), 2))
					cust_rate,
					CONCAT('$',FORMAT((carrier_line_haul * carrier_line_haul_amount) + 
					(carrier_detention * carrier_detention_amount) + 
					(carrier_tonu * carrier_tonu_amount) + 
					(carrier_unload_load * carrier_unload_load_amount) +
					(carrier_fuel * carrier_fuel_amount) + 
					(carrier_other * carrier_other_amount),2))
					carrier_rate,
					CONCAT('$',FORMAT(((cust_line_haul * cust_line_haul_amount) + 
					(cust_detention * cust_detention_amount) + 
					(cust_tonu * cust_tonu_amount) + 
					(cust_unload_load * cust_unload_load_amount) +
					(cust_fuel * cust_fuel_amount) + 
					(cust_other * cust_other_amount))
					-
					((carrier_line_haul * carrier_line_haul_amount) + 
					(carrier_detention * carrier_detention_amount) + 
					(carrier_tonu * carrier_tonu_amount) + 
					(carrier_unload_load * carrier_unload_load_amount) +
					(carrier_fuel * carrier_fuel_amount) + 
					(carrier_other * carrier_other_amount)),2)) profit
					FROM `load` l, customer c";
	$_REQUEST['user_id'] > 0 ? $sql .= " WHERE l.order_by = $_REQUEST[user_id] AND " : $sql .= ' WHERE ' ;
	$sql .= "		l.customer_id = c.customer_id
					AND activity_date >= '$mon'
					AND activity_date <= '$fri
					AND (cancelled is null
					OR cancelled = 0)'
					order by activity_date";
		$result = db_query($sql);
		//echo $sql."<br>";
		if(db_error())
		{
			echo $sql."<br>";
			echo db_error();
		}
		$c .= "<tbody class='weekly'>\n";
		while($r = db_fetch_array($result))
		{
			$c .= "<tr>\n";
			for($i=0;$i<=db_num_fields($result);$i++)
			{
				$c .= "<td class='".db_fieldname($result, $i)."'>$r[$i]</td>\n";
			}
			$c .= "</tr>\n";
		}
		$c .= "</tbody>\n";
		//$v = new view($sql);
		//return $v->render();
		return $c;
}

function get_summary($start, $end)
{
	require_once('includes/view.php');
$sql = "	SELECT '', 'Total Loads:', count(c.name) load_count,
					CONCAT('$', FORMAT(sum((cust_line_haul * cust_line_haul_amount) + 
					(cust_detention * cust_detention_amount) + 
					(cust_tonu * cust_tonu_amount) + 
					(cust_unload_load * cust_unload_load_amount) +
					(cust_fuel * cust_fuel_amount) + 
					(cust_other * cust_other_amount)), 2))
					sum_cust_rate,
					CONCAT('$', FORMAT(sum((carrier_line_haul * carrier_line_haul_amount) + 
					(carrier_detention * carrier_detention_amount) + 
					(carrier_tonu * carrier_tonu_amount) + 
					(carrier_unload_load * carrier_unload_load_amount) +
					(carrier_fuel * carrier_fuel_amount) + 
					(carrier_other * carrier_other_amount)), 2))
					sum_carrier_rate,
					CONCAT('$',FORMAT(sum(((cust_line_haul * cust_line_haul_amount) + 
					(cust_detention * cust_detention_amount) + 
					(cust_tonu * cust_tonu_amount) + 
					(cust_unload_load * cust_unload_load_amount) +
					(cust_fuel * cust_fuel_amount) + 
					(cust_other * cust_other_amount))
					-
					((carrier_line_haul * carrier_line_haul_amount) + 
					(carrier_detention * carrier_detention_amount) + 
					(carrier_tonu * carrier_tonu_amount) + 
					(carrier_unload_load * carrier_unload_load_amount) +
					(carrier_fuel * carrier_fuel_amount) + 
					(carrier_other * carrier_other_amount))), 2)) sum_profit
					FROM `load` l, customer c";
	$_REQUEST['user_id'] > 0 ? $sql .= " WHERE l.order_by = $_REQUEST[user_id] AND " : $sql .= ' WHERE ' ;
	$sql .= "		
					l.customer_id = c.customer_id
					AND activity_date >= '$start'
					AND activity_date <= '$end'
					AND (cancelled is null
					OR cancelled = 0)";
		$result = db_query($sql);
		if(db_error())
		{
			echo $sql."<br>";
			echo db_error();
		}
		$c .= "<tbody class='summary'>\n";
		while($r = db_fetch_array($result))
		{
			$c .= "<tr>\n";
			for($i=0;$i<=db_num_fields($result);$i++)
			{
				$c .= "<td class='".db_fieldname($result, $i)."'>$r[$i]</td>\n";
			}
			$c .= "</tr>\n";
		}
		$c .= "</tbody>\n";
		//$v = new view($sql);
		//return $v->render();
		return $c;
}

function get_month($num)
{
	$months = Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September','October', 'November', 'December');
	return $months[$num-1];
}
?>