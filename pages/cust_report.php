<?php
require_once('includes/Template.php');
$GLOBALS['page_title'] = 'Customer Reports';
$t = new Template();
$t->register_modifier("array2query", "array2query");
$t->register_modifier("in_array", "in_array");
$t->assign('users', get_users());
$_GET['user_id'] > 0 ? $t->assign('username', get_username($_GET['user_id'])) : '';
isset($_GET['end_date']) && $_GET['end_date'] != '' ? $end_date = $_GET['end_date'] : $end_date = date('Y-m-d');
isset($_GET['start_date']) && $_GET['start_date'] != '' ? $start_date = $_GET['start_date'] : $start_date = date('Y-m-d');
$t->assign('params', $_GET);
$t->assign('start_date', $start_date);
$t->assign('end_date', $end_date);
$t->assign('cust', DB::to_array(get_report($start_date, $end_date, $user_id)));
echo $t->fetch(App::$temp.'cust_report.tpl');

function get_report($start, $end, $user_id=null){
	$sql .= "select customer_id
					, name
					, (select username from `users` u where user_id = c.acct_owner) cust_rep
					, (select count(*)
						from `load`
						where customer_id = c.customer_id
						and activity_date >='$start'
						and activity_date <='$end'
						and load_id in (select lw.load_id from load_warehouse lw where lw.type = 'PICK' and lw.complete = 1)
						) load_count
					, IFNULL((select sum(IF(wc_active, profit-((profit * .01) * wc_percent), profit)) profit
						from load_report_totals
						where customer_id = c.customer_id
						and activity_date >='$start'
						and activity_date <='$end'
						and load_id in (select lw.load_id from load_warehouse lw where lw.type = 'PICK' and lw.complete = 1)
						group by customer_id), 0) profit
					, IFNULL((select sum(cust_rate) 
						from load_report_totals
						where customer_id = c.customer_id
						and activity_date >='$start'
						and activity_date <='$end'
						and load_id in (select lw.load_id from load_warehouse lw where lw.type = 'PICK' and lw.complete = 1)
						group by customer_id), 0) gross_revenue
	from customer c
	where (select count(*) from `load` where customer_id = c.customer_id) > 0
	AND (select count(*)
						from `load`
						where customer_id = c.customer_id
						and activity_date >='$start'
						and activity_date <='$end'
						and load_id in (select lw.load_id from load_warehouse lw where lw.type = 'PICK' and lw.complete = 1)
						) > 0";
	isset($_GET['user_id']) && $_GET['user_id']>0 ? $sql .= " and c.acct_owner = $_GET[user_id]" : '';
	if(isset($_GET['order'])){
		$sql .= " ORDER BY $_GET[order]";
		isset($_GET['dir']) ? $sql .= " $_GET[dir]" : '';
	}
	
	$re = DB::query($sql);
	if(DB::error()){
		echo "$sql<br>";
		echo DB::error();
	}
	return $re;
}

function get_users(){
	$sql = "SELECT *
			FROM `users`";
	$re = DB::query($sql);
	$ary = Array('');
	while($r = DB::fetch_assoc($re)){
		$ary[$r['user_id']] = $r['username'];
	}
	return $ary;
}

function get_username($user_id){
	$sql = "SELECT username
			FROM `users`
			WHERE user_id = $user_id";
	$re = DB::query($sql);
	$r = DB::fetch_assoc($re);
	return $r['username'];
}
?>