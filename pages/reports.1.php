<?php
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
	$c = "<style media='print' type='text/css'>
			#selector, #print_button, .menu{display:none}
			#logo{visibility:visible}
		</style>";
	$c .= "<style media='screen' type='text/css'>#logo{display:none}</style>";
		
	$c .='<table id="selector"><tr>';
	$c .= "<th>Choose a year</th>";
	$c .= "<th>Choose a month</th>";
	//$c .= "<th>Choose a day</th>";
	$c .= "</tr><tr>";
	$c .= "<form method='get'><td>";
	$c .= "
		<select id='start_year' name='start_year' onchange='set_start_days()'>
			".get_years()."
		</select>";
	$c .='</td><td>';
	$c .="
		<select id='start_month' name='start_month' onchange='set_start_days()'>
			".get_month_select()."
		</select>";
	$c .='</td><td>';
	
	$c .= "
		<input type='hidden' name='page' value='reports'>";
	
	$c .= "</tr><tr><td>";
	$c .= "<input type='submit' name='action' value='Report'>";
	$c .='</td></form></tr></table>';
	
	return $c;
}

function get_month_select()
{
	$month_num = date('n');
	
	$months = Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September','October', 'November', 'December');
	$c='';
	$m=1;
	foreach($months as $month)
	{
		if($m == $month_num)
		{
			$c .= "\n<option value='$m' selected>$month</option>";
		}else{
			$c .= "\n<option value='$m'>$month</option>";
		}
		$m++;
	}
	return $c;
}

function get_years()
{
	$start_year = 2007;
	$cur_year = date('Y');
	for($y=$start_year;$y <= $cur_year;$y++)
	{
		if($y == $cur_year)
		{
			$c .= "\n<option value='$y' selected>$y</option>";
		}else{
			$c .= "\n<option value='$y'>$y</option>";
		}
	}
	return $c;
}

function get_report()
{
	
	$first_day_str = "1-".get_month($_REQUEST['start_month'])."-".$_REQUEST['start_year'];
	$first_of_month = date ('Y-m-d', strtotime($first_day_str));
	$start_date = $_REQUEST['start_year']."-".str_pad($_REQUEST['start_month'], 2, '0', STR_PAD_LEFT)."-01";
	$start_week_day = "1";
	
	$first_sat_str = "first sat ".get_month($_REQUEST['start_month'])."-".$_REQUEST['start_year'];
	$first_mon_str = "first sun ".get_month($_REQUEST['start_month'])."-".$_REQUEST['start_year'];
	$end_date = date ('Y-m-d', strtotime($first_sat_str));
	
	$end_week_day = date ('j', strtotime($first_sat_str));
	
	$first_week = date('W', strtotime($first_day_str));
	//$first_mon_date = date('d', strtotime($first_mon_str));
	$first_mon_date = date('Y-m-d', strtotime($first_mon_str));
	//echo "first_mon_date:$first_mon_date<br>";
	$day_count =date('t', strtotime($first_day_str));
	//$last_day_week_str = ($day_count-1)."-".$_REQUEST['start_month']."-".$_REQUEST['start_year'];
	$last_day_week_str = ($day_count)."-".$_REQUEST['start_month']."-".$_REQUEST['start_year'];
	$last_day_str = "$day_count-".$_REQUEST['start_month']."-".$_REQUEST['start_year'];
	
	$last_of_month = date ('Y-m-d', strtotime($last_day_str));
	$last_week = date('W', strtotime($last_day_week_str));
	$first_week > $last_week ? $last_week = 53 :'' ; 
	$week_count = ($last_week+1)- ($first_week);
	//echo $week_count;
	//$c = "<div class='title'>Domestic Transport Solutions (DTS)</div>";
	$c .= "<input type='button' id='print_button' value='Print' onclick='print();'";
	$c .= "<img id='logo' src='http://".IMG_ROOT."/dts.gif'>";
	$c .= "<div class='title'>".get_month($_REQUEST['start_month'])." ".$_REQUEST['start_year']."</div>";
	$c .= "<table width='100%'>\n";
	
	for($w=1;$w <= $week_count; $w++)
	{
		//echo "start date:$start_date<br>";
		//echo "end date:$end_date<br>";
		$c .= "<thead class='weekly_head'><tr><td colspan=6>Week of $_REQUEST[start_month]/$start_week_day thru $_REQUEST[start_month]/$end_week_day</td></tr>\n";
		
		$c .= "<tr><th>Date</th><th>Load #</th><th>Customer</th><th>Cust Rate</th><th>Carr Rate</th><th>Profit</th></tr></thead>\n";
		//echo "start_date:$start_date<br />end_date:$end_date";
		$c .= get_weekly($start_date, $end_date);
		$c .= get_summary($start_date, $end_date);
		//echo "week $w<br>";
		//echo "start_week_day:$start_week_day<br>";
		//echo "start_date:$start_date<br>";
		//echo "first_mon_date:".strtotime($first_mon_date)."<br>";
		//echo "first day of week:".date('N', strtotime($start_date." + 7 days"))."<br>";
		if(date('N', strtotime($start_date." + 7 days")) == 7)
		//if(strtotime($first_mon_date) < strtotime($start_date." + 7 days"))
		//If the beginning of the next week is greater than the first day of the month
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
					FROM `load` l, customer c
					WHERE l.customer_id = c.customer_id
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
					FROM `load` l, customer c
					WHERE l.customer_id = c.customer_id
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