<?php
ini_set('display_errors', 'On');//Debug only
require_once("includes/portal.php");
require_once("includes/dts_table.php");
require_once("includes/Template.php");
require_once("includes/DB.php");
require_once('includes/html_form.php');
$l = new lane();
echo $l->render();

class lane {
	var $search = 'Search';
	
	function __construct(){
		
	}
	
	function render(){
		if(isset($_REQUEST[$this->search])){
			$c = $this->get_search_edit();
			$c .= $this->get_search_results();
			return $c;
		}else{
			return $this->get_search_edit();
		}
	}
	
	function get_search_edit(){
		$t = new Template();
		$t->assign('load_types', dts_table::$load_type);
		$t->assign('form', $_REQUEST);
		return $t->fetch(App::getTempDir().'lane_search_form.tpl');
	}
	
	function get_search_results(){
		$c ='';
		
		$c .= '<center><h2>Lane Search Results</h2>';
		
		$sql = "	SELECT l.load_id, CONCAT(pw.city, ', ', pw.state) pickup, CONCAT(dw.city, ', ', dw.state) destination
						, l.load_type
					FROM `load` l, load_warehouse pick, load_warehouse dest, warehouse pw, warehouse dw
					WHERE dest.load_id = l.load_id
					AND pick.load_id = l.load_id
					AND pick.type = 'PICK'
					AND dest.type = 'DEST'
					AND pw.warehouse_id = pick.warehouse_id
					AND dw.warehouse_id = dest.warehouse_id";
		$clause = 'AND';
		$where='';
		$binds = [];
		if(isset($_REQUEST['start_activity_date']) && $_REQUEST['start_activity_date'] != ''){
                    $binds[] = dateToMySQL($_REQUEST['start_activity_date']);
                    $where .= " $clause l.activity_date >= ?";
		}else{
                    $c .= 'Please select an starting activity date.<br>';
		}
		
		if(isset($_REQUEST['end_activity_date']) && $_REQUEST['end_activity_date'] != ''){
                    $binds[] = dateToMySQL($_REQUEST['end_activity_date']);
                    $where .= " $clause l.activity_date <= ?";
		}else{
                    $c .= 'Please select an ending activity date.<br>';
		}
					
		if(isset($_REQUEST['pickup_city']) && $_REQUEST['pickup_city'] !=''){
                    $binds[] = $_REQUEST['pickup_city'];
                    $where .= " $clause pw.city like ?";
		}else{
                    $c .= 'Please select a pickup city.<br>';
		}
			
		if(isset($_REQUEST['pickup_state']) && $_REQUEST['pickup_state'] !=''){
                    $binds[] = $_REQUEST['pickup_state'];
                    $where .= " $clause pw.state like ?";
		}else{
                    $c .= 'Please select a pickup state.<br>';
		}
		
		if(isset($_REQUEST['dest_city']) && $_REQUEST['dest_city'] !=''){
                    $binds[] = $_REQUEST['dest_city'];
                    $where .= " $clause dw.city like ?";
		}else{
                    $c .= 'Please select a destintion city.<br>';
		}
			
		if(isset($_REQUEST['dest_state']) && $_REQUEST['dest_state'] !=''){
                    $binds[] = $_REQUEST['dest_state'];
                    $where .= " $clause dw.state like ?";
		}else{
                    $c .= 'Please select a destintion state.<br>';
		}
		
		if(isset($_REQUEST['load_type']) && $_REQUEST['load_type'] != ''){
                    $binds[] = $_REQUEST['load_type'];
                    $where .= " $clause l.load_type = ?";
		}
		$sql .= $where;
		$sql .= ' ORDER BY l.activity_date';
		$t = new Template();
		$re = DB::query($sql, $binds);
		$t->assign('loads', DB::to_array($re));
		
		return $t->fetch(App::getTempDir().'lane_search_result.tpl');
		return $c;
	}
}
?>