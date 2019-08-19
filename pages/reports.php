<?php
ini_set('display_errors', 'On');//Debug only
require_once('includes/Template.php');
require_once('includes/global.php');
require_once('includes/dts_table.php');
require_once('includes/auth.php');
require_once('includes/DB.php');
require_once('includes/view.php');

if(logged_in_as('super admin')){
	echo "<title>DTS-Monthly Report</title>";
	
	echo month_report_form();
	if(get_action() == 'Report'){
		echo get_report();
	}
}

function month_report_form(){
	$t = new Template();
	$t->assign('users', get_users());
	$t->assign('year', date('Y'));
	//echo $_REQUEST['user_id'];
	isset($_REQUEST['user_id']) ? $t->assign('user_id', $_REQUEST['user_id']) : '';
	
	$t->assign('page', $_REQUEST['page']);
	$t->assign('form', $_REQUEST);
	$load_types = array_splice(dts_table::$load_type, 1);
	$t->assign('load_types', $load_types);
	return $t->fetch(App::getTempDir().'report_form.tpl');
}

function get_users(){
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

function get_report(){
	$start_month = intval($_REQUEST['start_Month']);
	//echo $start_month;
	$month_str = get_month($start_month);
	$first_day_str = "1-$month_str-".$_REQUEST['start_Year'];
	$debug='';
	$debug.="first_day_str = $first_day_str<br/>";
	
	$first_of_month = date ('Y-m-d', strtotime($first_day_str));
	$debug.="first_of_month = $first_of_month<br/>";
	
	$start_date = $_REQUEST['start_Year']."-".str_pad($start_month, 2, '0', STR_PAD_LEFT)."-01";
	$debug.="start_date = $start_date<br/>";
	$start_week_day = "1";
	
	$first_sat_str = "first sat $month_str ".$_REQUEST['start_Year'];
	$debug.="first_sat_str = $first_sat_str<br/>";
	
	$first_mon_str = "first sun $month_str-".$_REQUEST['start_Year'];
	$debug.="first_mon_str = $first_mon_str<br/>";
	
	$first_day_month = date ('D', strtotime($first_day_str));
	$debug.="first_day_month = $first_day_month<br/>";
	
	$first_day_num = date ('w', strtotime($first_day_str));
	$debug.="first_day_num = $first_day_num<br/>";
	
	if($first_day_month == "Sat"){
		$end_date = date ('Y-m-d', strtotime($first_of_month));
	}else{
		$end_date = date ('Y-m-d', strtotime($first_sat_str));
	}
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
	
	if($first_week > $last_week && $start_month == 12){
		$last_week = 53;
	}elseif($first_week > $last_week && $start_month == 1){
		$first_week = 1;
	}
	$debug.="first_week = $first_week<br/>";
	$week_count = ceil(($day_count + $first_day_num)/7);
	//$week_count = ceil($day_count/7);
	//$week_count = ($last_week+1)- ($first_week);
	$debug.="week_count = $week_count<br/>";
	
	//echo $debug;
	//echo $week_count;
	for($w=1;$w <= $week_count; $w++){
		$week = get_weekly($start_date, $end_date);
		
		$month[] = $week;
		//$weeks .= get_summary($start_date, $end_date);
		if(date('N', strtotime($start_date." + 7 days")) == 7){
			//echo "not sun";
			$start_week_day = date('j', strtotime($start_date." + 7 days"));
			$start_date = date('Y-m-d', strtotime($start_date." + 7 days"));
		}else{
			$start_week_day = date('j', strtotime($first_mon_date));
			$start_date = date('Y-m-d', strtotime($first_mon_date));
		
		}
		if(strtotime($end_date." + 7 days") > strtotime($last_day_str)){
		//If the end of the week is greater than the last day of the month
			$end_week_day = date('j', strtotime($last_day_str));
			$end_date = date('Y-m-d', strtotime($last_day_str));
		}else{
			$end_week_day = date('j', strtotime($end_date." + 7 days"));
			$end_date = date('Y-m-d', strtotime($end_date." + 7 days"));
		}
	}
	

	//print_r($month);
	//echo "$first_of_month, $last_of_month";
	//$summary= get_summary($first_of_month, $last_of_month);
	$t = new Template();
	isset($_GET['user_id']) ? $t->assign('user_id', $_GET['user_id']) : '';
	$t->assign('month', $month);
	$t->assign('month_str', get_month($_GET['start_Month']));
	$t->assign('start_year', $_GET['start_Year']);
	//$t->assign('weeks', $weeks);
	//$t->assign('summary', $summary);
	if(isset($_GET['user_id'])){
		
		//echo "user:".App::get_username($_GET['user_id']);
		$t->assign('user', App::get_username($_GET['user_id']));
		$t->assign('user_id', $_GET['user_id']);
	}
	$c = $t->fetch(App::getTempDir().'/monthly.tpl');
	return $c;
}

function get_occur($n)
{
	$occ = array("first", "second", "third", "fourth", "fifth");
	return $occ[$n];
}

function get_weekly($mon, $fri)
{
	
$sql = " SELECT	DATE_FORMAT(l.activity_date, '%m/%e') date
		, load_id
		, load_id id
		, c.name customer
		, l.cust_rate cust_rate
		, l.carrier_rate carrier_rate
		, wc_active
		";
	//isset($_GET['wcp']) ? $sql .= "	 , FORMAT(((profit * .01) * wc_percent), 2) wcp " : $sql .= ', FORMAT(IF(wc_active, profit-((profit * .01) * wc_percent), profit), 2) profit'; //old
	/*1/20/14 Added case statement to set profit to 0 if the user is only the booked with agent*/
	if(isset($_GET['user_id']) &&  $_GET['user_id'] > 0){
		$sql .= ", CASE WHEN c.acct_owner != $_GET[user_id] THEN 0 ";
	}else{
		$sql .= ", CASE ";
	}
	if(isset($_GET['wcp'])){
		//Show the WCP percentage
		$sql .= " WHEN wc_active = 1 THEN ((profit * .01) * wc_percent) ";
	}else{
		//Show the DTSP percentage
		$sql .= " WHEN wc_active = 1 THEN ((profit * .01) * (100-wc_percent)) ";
	}
	$sql .= "	
						ELSE profit
					END profit";
	
	//isset($_GET['wcp']) ? $sql .= "	ELSE ((profit * .01) * wc_percent) END wcp " : $sql .= ' ELSE CASE WHEN wc_active=1 THEN profit-((profit * .01) * wc_percent) ELSE profit END END profit';//Removed 1/21/14 per Joe
	
	//$dec1 = new DateTime('12/01/2011');
	//$mon_date = new DateTime($mon);
	if(strtotime($mon) >= strtotime('12/01/2011'))
	{
		/*12/3/13 Joe requested "Darrel. Can you make a quick change to our DB?  In the reports, can you change the percentage so that the carrier rep commission equal the profit. And not the current 33%" 
		old line ", IF(wc_active, profit-((profit * .01) * wc_percent), profit) * .3 carrier_rep_comm"
		*/
		$sql .= ", (SELECT username FROM users u, load_carrier lc WHERE lc.load_id = l.load_id AND u.user_id = lc.booked_with LIMIT 1) carrier_rep
			, (SELECT booked_with FROM load_carrier lc WHERE lc.load_id = l.load_id LIMIT 1) carrier_rep_id
			
			, IF(wc_active, profit-((profit * .01) * wc_percent), profit) carrier_rep_comm
			, (SELECT username FROM users u WHERE c.acct_owner = u.user_id) sales_rep
			, acct_owner sales_rep_id
			, IF(wc_active, profit-((profit * .01) * wc_percent), profit) * .1 sales_rep_comm";
		
		if(isset($_GET['user_id']) &&  $_GET['user_id'] > 0){
			$sql .= ", CASE WHEN c.acct_owner != $_GET[user_id] THEN 0 ";
		}else{
			$sql .= ", CASE ";
		}
		if(isset($_GET['wcp'])){
			$sql .= "	WHEN wc_active = 1
							THEN ((profit * .01) * wc_percent)";
		}else{
			//Show the DTSP percentage
			$sql .= "	WHEN wc_active = 1
							THEN ((profit * .01) * (100-wc_percent)) ";
		}
		
		$sql .= "			ELSE profit
						END total_comm
				/*, IF(wc_active, profit-((profit * .01) * wc_percent), profit) * .4 total_comm*/";
	}
	//isset($_GET['wcp']) ? $sql .= "	 , FORMAT(((l.profit * .01) * wc_percent), 2) wcp " : $sql .= ', FORMAT(profit, 2) profit';
		
$sql .= " 
		FROM load_report_totals ";
		
$sql .= " l
		, customer c ";
	$clause = 'WHERE';
	if(isset($_GET['wcp']))
	{
		$sql .= "	$clause wc_active = 1 ";
		$clause = 'AND';
	}

	if($_REQUEST['user_id'] > 0){
		//1/8/14 - Removed booked_with filter per Joe "we no longer pay employees for booking a load"
		$sql .= " $clause (c.acct_owner = $_REQUEST[user_id]
		OR load_id in (SELECT load_id FROM load_carrier WHERE booked_with = $_REQUEST[user_id])
		)";
		$clause = 'AND';
	}
	
	if(isset($_REQUEST['load_type']) && $_REQUEST['load_type'] != ''){
		$load_types = array_to_list($_REQUEST['load_type']);
		//$sql .= " $clause load_type = '$_REQUEST[load_type]' ";
		$sql .= " $clause load_type in ($load_types) ";
		$clause = 'AND';
	}
	
	$sql .= "		$clause l.customer_id = c.customer_id
					AND activity_date >= '$mon'
					AND activity_date <= '$fri'
					order by activity_date, load_id";
		$result = DB::query($sql);
		//echo $sql."<br><br>";
		if(DB::error())
		{
			echo "Error in get_weekly<br/>";
			echo $sql."<br/>";
			echo DB::error()."<br/>";
		}
		$t = new Template();
		isset($_GET['user_id']) ? $t->assign('user_id', $_GET['user_id']) : '';
		//echo App::get_username($_GET['user_id']);
		
		//isset($_GET['user_id']) ? $t->assign('user', App::get_username($_GET['user_id'])) : '';
		$t->assign('start_month', $_REQUEST['start_Month']);
		$t->assign('start_week_day', date('j', strtotime($mon)));
		$t->assign('end_week_day', date('j', strtotime($fri)));
		//$t->assign('weeks', DB::to_array($result));
		//return $t->fetch('/home/dts2828/www/dts/templates/weekly.new.tpl');
		$a['start_month'] = $_REQUEST['start_Month'];
		$a['start_week_day'] = date('j', strtotime($mon));
		$a['end_week_day'] = date('j', strtotime($fri));
		$a['loads'] =  DB::to_array($result);
		return $a;
}
/*function get_summary($start, $end)
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
if(strtotime($start) >= strtotime('12/01/2011'))
	{
$sql .= "	, '' carrier_rep
			, FORMAT(SUM(IF(wc_active, profit-((profit * .01) * wc_percent), profit) * .3), 2) carrier_rep_comm
			, '' sales_rep
			, FORMAT(SUM(IF(wc_active, profit-((profit * .01) * wc_percent), profit) * .1), 2) sales_rep_comm
			, FORMAT(SUM(IF(wc_active, profit-((profit * .01) * wc_percent), profit) * .4), 2) total_comm";
	}
$sql .= "		FROM load_report_totals";
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
	
	if($_REQUEST['user_id'] > 0)
	{
		$sql .= " $clause (customer_id in (SELECT customer_id FROM customer WHERE acct_owner = $_REQUEST[user_id])
				OR load_id in (SELECT load_id FROM load_carrier WHERE booked_with = $_REQUEST[user_id]))";
		$clause = 'AND';
	}
	
	$sql .= " $clause activity_date >= '$start'
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
}*/


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