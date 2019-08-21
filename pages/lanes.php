<?php
ini_set('display_errors', 'On');//Debug only
require_once("includes/portal.php");
require_once("includes/dts_table.php");
require_once("includes/Template.php");
require_once("includes/DB.php");
require_once('includes/html_form.php');
$l = new lane();
echo $l->render();

class lane //extends dts_table
{
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
	function get_search_edit_old(){
		$f = new html_form();
		$f->set_get();
		$i0 = new hidden_input('page', 'lanes');
		$f->add_input($i0);
		
		$i1 = new date_input('start_activity_date');
		$f->add_input($i1);
		
		$i1b = new date_input('end_activity_date');
		$f->add_input($i1b);
		
		$i2 = new text_input('pickup_city');
		$f->add_input($i2);
		
		$i3 = new text_input('pickup_state');
		$f->add_input($i3);
		
		$i4 = new text_input('dest_city');
		$f->add_input($i4);
		
		$i5 = new text_input('dest_state');
		$f->add_input($i5);
		
		$i6 = new submit_input($this->search, $this->search);
		$f->add_input($i6);
		$c = '<center><h2>Lane Search</h2>';
		$c .= $f->render();
		return $c;
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