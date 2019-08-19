<?php
require_once("reports.php");
if(logged_in())
{
	$_GET['user_id'] = get_user_ID();
	$_REQUEST['user_id'] = get_user_ID();
	if(!isset($_GET['start_Year']))
	{
		$_GET['start_Year'] = date('Y');
		$_REQUEST['start_Year'] = date('Y');
	}
	if(!isset($_GET['start_Month']))
	{
		$_GET['start_Month'] = date('m');
		$_REQUEST['start_Month'] = date('m');
	}
	echo "<br/>";
	//echo month_report_form();
	echo get_report();

}
?>