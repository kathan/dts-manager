<?php
require_once 'includes/Template.php';
require_once('includes/global.php');
require_once('includes/dts_table.php');
require_once('includes/auth.php');
require_once('includes/DB.php');
require_once('includes/view.php');

echo "<title>DTS - Monthly Reports</title>";
echo month_report_form();
if (Auth::loggedInAs('super admin')){
	if(get_action() === 'Report'){
		echo get_report($_REQUEST);
	}
}else if (isset($_GET['user_id']) && Auth::getUserId() === safe_get($_GET['user_id'])){
	if(get_action() === 'Report'){
		$opts = [
			'user_id' => Auth::getUserId(),
			'start_Month' => $_REQUEST['start_Month'],
			'start_Year' => $_REQUEST['start_Year']
		];

		echo get_report($opts);
	}
}else{
	echo "No access";
}

function month_report_form(){
	$t = new Template();
	$user_id = Auth::getUserId();
	if(Auth::loggedInAs('super admin')){
		$t->assign('users', get_users());
	}else{
		$t->assign('users', [$user_id => Auth::getUserName()]);
	}
	
	$t->assign('year', date('Y'));
	isset($user_id) ? $t->assign('user_id', $user_id) : '';
	
	$t->assign('page', $_REQUEST['page']);
	$t->assign('form', $_REQUEST);
	$load_types = array_splice(dts_table::$load_type, 1);
	$t->assign('load_types', $load_types);
	return $t->fetch(App::getTempDir().'report_form.tpl');
}

function get_users(){
	$sql = "SELECT *
			FROM `users`";
	$re = App::$db->query($sql);
	$ary = [];
	while($r = $re->fetch(PDO::FETCH_ASSOC)){
		$ary[$r['user_id']] = $r['username'];
	}
	return $ary;
}

function get_report($opts){
	$start_month = intval($opts['start_Month']);
	$month_str = get_month($start_month);
	$first_day_str = "1-$month_str-".$opts['start_Year'];
	$debug='';
	$debug.="first_day_str = $first_day_str<br/>";
	
	$first_of_month = date ('Y-m-d', strtotime($first_day_str));
	$debug.="first_of_month = $first_of_month<br/>";
	
	$start_date = $opts['start_Year']."-".str_pad($start_month, 2, '0', STR_PAD_LEFT)."-01";
	$debug.="start_date = $start_date<br/>";
	$start_week_day = "1";
	
	$first_sat_str = "first sat $month_str ".$opts['start_Year'];
	$debug.="first_sat_str = $first_sat_str<br/>";
	
	$first_mon_str = "first sun $month_str-".$opts['start_Year'];
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
	
	$debug.="start_Month = $opts[start_Month]<br/>";
	
	$first_week = date('W', strtotime($first_day_str));
	$debug.="first_week = $first_week<br/>";
	
	$first_mon_date = date('Y-m-d', strtotime($first_mon_str));
	$debug.="first_mon_date = $first_mon_date<br/>";
	
	$day_count =date('t', strtotime($first_day_str));
	$debug.="day_count = $day_count<br/>";
	
	$last_day_week_str = ($day_count)."-".$start_month."-".$opts['start_Year'];
	$debug.="last_day_week_str = $last_day_week_str<br/>";
	
	$last_day_str = "$day_count-".$start_month."-".$opts['start_Year'];
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
	$debug.="week_count = $week_count<br/>";
	
	for($w=1;$w <= $week_count; $w++){
		$week = get_weekly($start_date, $end_date, $opts);
		
		$month[] = $week;
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
	

	$t = new Template();
	isset($opts['user_id']) ? $t->assign('user_id', $opts['user_id']) : '';
	$t->assign('month', $month);
	$t->assign('month_str', get_month($opts['start_Month']));
	$t->assign('start_year', $opts['start_Year']);
	if(isset($opts['user_id'])){
		$t->assign('user', App::getUsername($opts['user_id']));
		$t->assign('user_id', $opts['user_id']);
	}
	$c = $t->fetch(App::getTempDir().'/monthly.tpl');
	return $c;
}

function get_occur($n){
	$occ = array("first", "second", "third", "fourth", "fifth");
	return $occ[$n];
}

function get_weekly($mon, $fri, $opts){
	$binds = [];
	$sql = " SELECT	DATE_FORMAT(l.activity_date, '%m/%e') date
			, load_id
			, load_id id
			, c.name customer
			, l.cust_rate cust_rate
			, l.carrier_rate carrier_rate
			, wc_active
			";
		/*1/20/14 Added case statement to set profit to 0 if the user is only the booked with agent*/
		if(isset($opts['user_id']) &&  $opts['user_id'] > 0){
			$binds[] = $opts['user_id'];
			$sql .= ", CASE WHEN c.acct_owner != ? THEN 0 ";
		}else{
			$sql .= ", CASE ";
		}
		if(isset($opts['wcp'])){
			//Show the WCP percentage
			$sql .= " WHEN wc_active = 1 THEN ((profit * .01) * wc_percent) ";
		}else{
			//Show the DTSP percentage
			$sql .= " WHEN wc_active = 1 THEN ((profit * .01) * (100-wc_percent)) ";
		}
		$sql .= "	
							ELSE profit
						END profit";
		
		
		if(strtotime($mon) >= strtotime('12/01/2011')){
			/*12/3/13 Joe requested "Darrel. Can you make a quick change to our DB?  In the reports, can you change the percentage so that the carrier rep commission equal the profit. And not the current 33%" 
			old line ", IF(wc_active, profit-((profit * .01) * wc_percent), profit) * .3 carrier_rep_comm"
			*/
			$sql .= ", (SELECT username FROM `users` u, load_carrier lc WHERE lc.load_id = l.load_id AND u.user_id = lc.booked_with LIMIT 1) carrier_rep
				, (SELECT booked_with FROM load_carrier lc WHERE lc.load_id = l.load_id LIMIT 1) carrier_rep_id
				
				, IF(wc_active, profit-((profit * .01) * wc_percent), profit) carrier_rep_comm
				, (SELECT username FROM `users` u WHERE c.acct_owner = u.user_id) sales_rep
				, acct_owner sales_rep_id
				, IF(wc_active, profit-((profit * .01) * wc_percent), profit) * .1 sales_rep_comm";
			
			if(isset($opts['user_id']) &&  $opts['user_id'] > 0){
				$sql .= ", CASE WHEN c.acct_owner != ? THEN 0 ";
				$binds[] = $opts['user_id'];
			}else{
				$sql .= ", CASE ";
			}
			if(isset($opts['wcp'])){
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
			
	$sql .= " 
			FROM load_report_totals ";
			
	$sql .= " l
			, customer c ";
		$clause = 'WHERE';
		if(isset($opts['wcp'])){
			$sql .= "	$clause wc_active = 1 ";
			$clause = 'AND';
		}
	
		if($opts['user_id'] > 0){
			//1/8/14 - Removed booked_with filter per Joe "we no longer pay employees for booking a load"
			$binds[] = $opts['user_id'];
			$binds[] = $opts['user_id'];
			$sql .= " $clause (c.acct_owner = ?
			OR load_id in (SELECT load_id FROM load_carrier WHERE booked_with = ?)
			)";
			$clause = 'AND';
		}
		
		if(isset($opts['load_type']) && $opts['load_type'] != ''){
			$load_types = array_to_list($opts['load_type']);
			$sql .= " $clause load_type in ($load_types) ";
			$clause = 'AND';
		}
		
		$binds[] = $mon;
		$binds[] = $fri;
		$sql .= "		$clause l.customer_id = c.customer_id
						AND activity_date >= ?
						AND activity_date <= ?
						order by activity_date, load_id";
			$stmt = App::$db->prepare($sql);
			$result = $stmt->execute($binds);
			if(!$result){
				echo "Error in get_weekly<br/>";
				echo $sql."<br/>";
				echo App::$db->errorCode()."<br/>";
			}
			$t = new Template();
			isset($opts['user_id']) ? $t->assign('user_id', $opts['user_id']) : '';
			
			$t->assign('start_month', $opts['start_Month']);
			$t->assign('start_week_day', date('j', strtotime($mon)));
			$t->assign('end_week_day', date('j', strtotime($fri)));
			$a['start_month'] = $opts['start_Month'];
			$a['start_week_day'] = date('j', strtotime($mon));
			$a['end_week_day'] = date('j', strtotime($fri));
			$a['loads'] =  DB::to_array($stmt);
			return $a;
}

function array_to_list($ar){
	$l = '';
	$i=0;
	foreach($ar as $a){
		$i > 0 ? $l .= "," : '';
		$l .= "'$a'";
		
		$i++;
	}
	return $l;
}

function get_month($num){
	$months = Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September','October', 'November', 'December');
	return $months[$num-1];
}
?>