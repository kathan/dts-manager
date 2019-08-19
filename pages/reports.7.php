<?php
	require_once"/home/dts2828/include/Template.php";
	require_once"/home/dts2828/include/DB.php";
	require_once"includes/global.php";
	require_once"includes/dts_table.php";
	require_once"includes/auth.php";
	require_once('DB.php');
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
	$t->assign('year', date('Y'));
	//echo $_REQUEST['user_id'];
	$t->assign('user_id', $_REQUEST['user_id']);
	$t->assign('page', $_REQUEST['page']);
	$t->assign('form', $_REQUEST);
	$load_types = array_splice(dts_table::$load_type, 1);
	$t->assign('load_types', $load_types);
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
	$start_month = intval($_REQUEST['start_Month']);
	//echo $start_month;
	$month_str = get_month($start_month);
	$first_day_str = "1-$month_str-".$_REQUEST['start_Year'];
	$debug.="first_day_str = $first_day_str<br/>";
	
	$first_of_month = date ('Y-m-d', strtotime($first_day_str));
	$debug.="first_of_month = $first_of_month<br/>";
	
	$start_date = $_REQUEST['start_Year']."-".str_pad($start_month, 2, '0', STR_PAD_LEFT)."-01";
	$debug.="start_date = $start_date<br/>";
	$start_week_day = "1";
	
	$first_sat_str = "first sat $month_str-".$_REQUEST['start_Year'];
	$debug.="first_sat_str = $first_sat_str<br/>";
	
	$first_mon_str = "first sun $month_str-".$_REQUEST['start_Year'];
	$debug.="first_mon_str = $first_mon_str<br/>";
	
	$end_date = date ('Y-m-d', strtotime($first_sat_str));
	$debug.="end_date = $end_date<br/>";
	
	$end_week_day = date ('j', strtotime($first_sat_str));
	$debug.="end_week_day = $end_week_day<br/>";
	
	//$month = date ('j', strtotime($first_day_str));
	$debug.="start_Month = $_REQUEST[start_Month]<br/>";
	
	$first_week = date('W', strtotime($first_day_str));
	//$first_week = date('W', strtotime($first_sat_str));
	$debug.="first_week = $first_week<br/>";
	
	$first_mon_date = date('Y-m-d', strtotime($first_mon_str));
	$debug.="first_mon_date = $first_mon_date<br/>";
	
	$day_count =date('t', strtotime($first_day_str));
	//echo $day_count;
	$debug.="day_count = $day_count<br/>";
	
	$last_day_week_str = ($day_count)."-".$start_month."-".$_REQUEST['start_Year'];
	$debug.="last_day_week_str = $last_day_week_str<br/>";
	
	$last_day_str = "$day_count-".$start_month."-".$_REQUEST['start_Year'];
	$debug.="last_day_str = $last_day_str<br/>";
	
	$last_of_month = date ('Y-m-d', strtotime($last_day_str));
	$debug.="last_of_month = $last_of_month<br/>";
	
	$last_week = date('W', strtotime($last_day_week_str));
	$debug.="last_week = $last_week<br/>";
	
	if($first_week > $last_week && $start_month == 12)
	{
		$last_week = 53;
	}elseif($first_week > $last_week && $start_month == 1)
	{
		$first_week = 1;
	}
	$debug.="first_week = $first_week<br/>";
	//$week_count = ceil(($day_count - $start_month)/7);
	$week_count = ceil($day_count/7);
	//$week_count = ($last_week+1)- ($first_week);
	$debug.="week_count = $week_count<br/>";
	
	//echo $debug;
	//echo $week_count;
	for($w=1;$w <= $week_count; $w++)
	{
		$weeks .= get_weekly($start_date, $end_date);
		$weeks .= get_summary($start_date, $end_date);
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
	//echo "$first_of_month, $last_of_month";
	$summary= get_summary($first_of_month, $last_of_month);
	$t = new Template();
	$t->assign('month_str', get_month($_REQUEST['start_Month']));
	$t->assign('start_year', $_REQUEST['start_Year']);
	$t->assign('weeks', $weeks);
	$t->assign('summary', $summary);
	
	$c = $t->fetch('/home/dts2828/www/dts/templates/monthly.tpl');
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
$sql = " SELECT	DATE_FORMAT(l.activity_date, '%m/%e') date
		, load_id
		, IFNULL(CONCAT('<span class=\"', load_type,'\">', load_id, '</span>'), load_id) id
		, c.name customer
		, FORMAT(l.cust_rate, 2) cust_rate
		, FORMAT(l.carrier_rate, 2) carrier_rate";
	isset($_GET['wcp']) ? $sql .= "	 , FORMAT(((profit * .01) * wc_percent), 2) wcp " : $sql .= ', FORMAT(IF(wc_active, profit-((profit * .01) * wc_percent), profit), 2) profit'; //old
	
	//isset($_GET['wcp']) ? $sql .= "	 , FORMAT(((l.profit * .01) * wc_percent), 2) wcp " : $sql .= ', FORMAT(profit, 2) profit';
		
$sql .= " 
		FROM load_report_totals";
		
$sql .= " l
		, customer c ";
	$clause = 'WHERE';
	if(isset($_GET['wcp']))
	{
		$sql .= "	$clause wc_active = 1 ";
		$clause = 'AND';
	}
	
	
	if($_REQUEST['user_id'] > 0)
	{
		$sql .= " $clause c.acct_owner = $_REQUEST[user_id] ";
		$clause = 'AND';
	}
	
	if(isset($_REQUEST['load_type']) && $_REQUEST['load_type'] != '')
	{
		$load_types = array_to_list($_REQUEST['load_type']);
		//$sql .= " $clause load_type = '$_REQUEST[load_type]' ";
		$sql .= " $clause load_type in ($load_types) ";
		$clause = 'AND';
	}
	
	$sql .= "		$clause l.customer_id = c.customer_id
					AND activity_date >= '$mon'
					AND activity_date <= '$fri'
					order by activity_date";
		$result = DB::query($sql);
		//echo $sql."<br><br>";
		if(DB::error())
		{
			echo "Error in get_weekly<br>";
			echo $sql."<br>";
			echo DB::error();
		}
		$t = new Template();
		
		$t->assign('start_month', $_REQUEST['start_Month']);
		$t->assign('start_week_day', date('j', strtotime($mon)));
		$t->assign('end_week_day', date('j', strtotime($fri)));
		$t->assign('weeks', DB::to_array($result));
		return $t->fetch('/home/dts2828/www/dts/templates/weekly.tpl');
}
function get_summary($start, $end)
{
	//require_once('includes/view.php');
$sql = "	SELECT ''
		, 'Total Loads:'
		,  count(*)load_count
		, FORMAT(sum(cust_rate), 2)
		, FORMAT(sum(carrier_rate), 2)
		 ";
//isset($_GET['wcp']) ? $sql .= "	 , FORMAT(((l.sum_profit * .01) * wc_percent), 2) wc_amount " : $sql .= ', FORMAT(sum_profit, 2)';
isset($_GET['wcp']) ? $sql .= "	 , FORMAT(sum((profit * .01) * wc_percent), 2) wc_amount " : $sql .= ',FORMAT(sum(IF(wc_active,profit-((profit * .01) * wc_percent),profit)), 2) profit';

$sql .= " 
		FROM load_report_totals";
	$clause = 'WHERE';
if(isset($_GET['wcp']))
{
	$sql .= "	$clause wc_active = 1 ";
	$clause = 'AND';
}

if(isset($_REQUEST['load_type']) && $_REQUEST['load_type'] != '')
	{
		$load_types = array_to_list($_REQUEST['load_type']);
		//$sql .= " $clause load_type = '$_REQUEST[load_type]' ";
		$sql .= " $clause load_type in ($load_types) ";
		$clause = 'AND';
	}
	
	$_REQUEST['user_id'] > 0 ? $sql .= " $clause customer_id in (SELECT customer_id FROM customer WHERE acct_owner = $_REQUEST[user_id]) AND " : $sql .= " $clause " ;
	
	$sql .= " activity_date >= '$start'
	AND activity_date <= '$end'
	";
		//echo "$sql<br>";
		$result = DB::query($sql);
		if(DB::error())
		{
			echo "Error in get_summary<br>";
			echo $sql."<br>";
			echo DB::error();
		}
		$t = new Template();
		
		$summary = DB::to_array($result);
		//print_r($summary);
		$t->assign('summary', $summary);
		$c = $t->fetch('/home/dts2828/www/dts/templates/summary.tpl');
		return $c;
}
function get_summary_old($start, $end)
{
	//require_once('includes/view.php');
$sql = "	SELECT ''
		, 'Total Loads:'
		,  load_count
		, sum_cust_rate
		, sum_carrier_rate
		 ";
//isset($_GET['wcp']) ? $sql .= "	 , FORMAT(((l.sum_profit * .01) * wc_percent), 2) wc_amount " : $sql .= ', FORMAT(sum_profit, 2)';
isset($_GET['wcp']) ? $sql .= "	 , FORMAT(((l.sum_profit * .01) * wc_percent), 2) wc_amount " : $sql .= ', FORMAT(l.sum_profit-((l.sum_profit * .01) * wc_percent), 2) ';

$sql .= " 
		FROM (
	SELECT
	wc_percent
	, count(load_id) load_count
	, FORMAT(sum( (cust_line_haul * cust_line_haul_amount) +
					(cust_detention * cust_detention_amount) +
					(cust_tonu * cust_tonu_amount) +
					(cust_unload_load * cust_unload_load_amount) +
					(cust_fuel * cust_fuel_amount) +
					(cust_other * cust_other_amount)), 2) sum_cust_rate 
	, FORMAT(sum( (carrier_line_haul * carrier_line_haul_amount) +
				(carrier_detention * carrier_detention_amount) +
				(carrier_tonu * carrier_tonu_amount) +
				(carrier_unload_load * carrier_unload_load_amount) +
				(carrier_fuel * carrier_fuel_amount) +
				(carrier_other * carrier_other_amount)), 2) sum_carrier_rate
	, sum(IF(wc_active, ((cust_line_haul * cust_line_haul_amount) +
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
					(carrier_other * carrier_other_amount)) -
					((cust_line_haul * cust_line_haul_amount) +
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
					(carrier_other * carrier_other_amount)*wc_percent),
					 (cust_line_haul * cust_line_haul_amount) +
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
					(carrier_other * carrier_other_amount))) sum_profit
	FROM `load`";
	$clause = 'WHERE';
if(isset($_GET['wcp']))
{
	$sql .= "	$clause wc_active = 1 ";
	$clause = 'AND';
}
	
	$_REQUEST['user_id'] > 0 ? $sql .= " $clause customer_id in (SELECT customer_id FROM customer WHERE acct_owner = $_REQUEST[user_id]) AND " : $sql .= " $clause " ;
	
	$sql .= " activity_date >= '$start'
	AND activity_date <= '$end'
	AND (cancelled is null OR cancelled = 0)
	) l";
		echo "$sql<br>";
		$result = DB::query($sql);
		if(DB::error())
		{
			echo $sql."<br>";
			echo DB::error();
		}
		$t = new Template();
		
		$summary = DB::to_array($result);
		//print_r($summary);
		$t->assign('summary', $summary);
		$c = $t->fetch('/home/dts2828/www/dts/templates/summary.tpl');
		return $c;
}


function get_weekly_old($mon, $fri)
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
	
	$clause = 'WHERE';
	if($_REQUEST['user_id'] > 0)
	{
		$sql .= " $clause c.acct_owner = $_REQUEST[user_id] ";
		$clause = 'AND';
	}
	
	if(isset($_REQUEST['wcp']))
	{
		//gp * wc_perc
		$sql .= " $clause l.wc_active = 1 ";
		$clause = 'AND';
	}
	$sql .= "		$clause l.customer_id = c.customer_id
					AND activity_date >= '$mon'
					AND activity_date <= '$fri
					AND (cancelled is null
					OR cancelled = 0)'
					order by activity_date";
		$result = DB::query($sql);
		//echo $sql."<br>";
		if(DB::error())
		{
			echo $sql."<br>";
			echo DB::error();
		}
		$t = new Template();
		
		$t->assign('start_month', $_REQUEST['start_Month']);
		$t->assign('start_week_day', date('j', strtotime($mon)));
		$t->assign('end_week_day', date('j', strtotime($fri)));
		$t->assign('weeks', DB::to_array($result));
		return $t->fetch('/home/dts2828/www/dts/templates/weekly.tpl');
}

function array_to_list($ar)
{
	$l = '';
	$i=0;
	foreach($ar as $a)
	{
		$i > 0 ? $l .= "," : '';
		$l .= "'$a'";
		
		$i++;
	}
	return $l;
}

function get_month($num)
{
	$months = Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September','October', 'November', 'December');
	return $months[$num-1];
}
?>