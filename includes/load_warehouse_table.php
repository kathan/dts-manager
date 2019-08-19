<?php
require_once "includes/app.php";
require_once "dts_table.php";
require_once('includes/warehouse_table.php');
class load_warehouse_table extends dts_table
{
	var $warehouse_id;
	var $load_id;
	function load_warehouse_table()
	{
		$this->dts_table('load_warehouse');
		if(isset($_REQUEST['load_id']))
		{
			$this->load_id = $_REQUEST['load_id'];
		}
		
		if(isset($_REQUEST['warehouse_id']))
		{
			$this->warehouse_id = $_REQUEST['warehouse_id'];
		}
		
		$this->dts_table('load_warehouse');
		$ob =& $this->get_column('warehouse_id');
		$ob->set_parent_label_column('name');
		$col =& $this->get_column('open_time');
		$col->set_value_list($this->times);
		$col =& $this->get_column('close_time');
		$col->set_value_list($this->times);
	}
	
	function render()
	{
		$code = "<title>".SITE_NAME."-Loads</title>";
		$code .= $this->db_script();
		$code .= $this->portal_script();
		$code .= $this->module_script();
		$code .= $this->sortable_script();
		$code .= $this->popup_script();
		$code .= '<link rel="stylesheet" href="'.APP_ROOT.'/style.css" type="text/css" media="all">';
		$code .= "<div class='content load_content' id='content'>";
		switch(get_action())
		{
			case $this->edit_str:
				$code .= $this->get_load_warehouse_edit();
				break;
			default:
				$code .= $this->get_warehouses();
				break;
		}
		return $code;
	}
	
	function do_add()
	{
		$this->add();
		return $this->feedback;
	}
	
	function get_warehouses()
	{
		require_once"includes/portal.php";
		$q = new portal("	SELECT 	*
							FROM `load_warehouse`
							WHERE load_id = $_REQUEST[load_id]");
	
		//$q->set_table('load_warehouse');
		//$q->set_primary_key('load_id');
		return $q->render();
	}
	function get_load($load_id)
	{
		$sql ="	SELECT *
				FROM `load`
				WHERE load_id = $load_id";
		$r = db_query($sql);
		return db_fetch_assoc($r);
	}
	
	function get_load_carrier($load_id, $carrier)
	{
		$sql ="	SELECT *
				FROM `load_carrier`
				WHERE load_id = $load_id
				AND carrier_id = $carrier_id";
		$r = db_query($sql);
		return db_fetch_assoc($r);
	}
	
	function get_load_warehouse_edit()
	{
		require_once('Template.php');
		$t = new Template();
		$lt = $this->get_load($this->load_id);
		$lw = $this->get_load_warehouse($this->load_id, $this->warehouse_id);
		$wt = new warehouse_table();
		$t->assign('warehouse', $wt->edit_warehouse());
		$t->assign('lw', $lw);
		$t->assign('times', $this->times);
		
		$t->assign('order_by', $this->get_user_name($lt['order_by']));
		$t->assign('scheduled_with', $this->get_user_name($lw['scheduled_with']));
		return $t->fetch(App::$temp.'load_warehouse_edit.tpl');
	}
	function get_load_warehouse_edit_old2()
	{
		require_once('includes/warehouse_table.php');
		$wt = new warehouse_table();
		$lt = $this->get_load($this->load_id);
		$lw = $this->get_load_warehouse($this->load_id, $this->warehouse_id);
		$c = $this->db_script();
		//$c .= $this->check_size();
		$c .= '<!-- get_load_warehouse_edit start -->';
		$c .='<table><tr>';
		//==== Load Module ====
		$c .= '<table style="border:1px solid black"><tr>';
		$c .= '<th colspan=4>Load</td>';
		$c .= '</tr><tr>';
		$c .= "<td>Load ID:</td><td class='faux_edit'>$lw[load_id]</td>";
		$c .= "<td>Type:</td><td class='faux_edit'>$lw[type]</td>";
		$c .= '</tr><tr>';
		
		$c .= "<td>Activity Date:</td><td>".$this->fetch_edit('activity_date', $lw['activity_date'])."</td>";
		$c .= "<td>Open Time:</td><td>".$this->fetch_edit('open_time', $lw['open_time'])."</td>";
		$c .= '</tr><tr>';
		$c .= '<td></td><td></td>';
		$c .= "<td>Close Time:</td><td>".$this->fetch_edit('close_time', $lw['close_time'])."</td>";
		$c .= '</tr><tr>';
		$c .= "<td>Creation Date:</td><td class='faux_edit'>$lw[creation_date]</td>";
		$c .= "<td>Scheduled With:</td><td class='faux_edit'>".$this->get_user_name($lw['scheduled_with'])."</td>";
		$c .= '</tr><tr>';
		$c .= '<td></td><td></td>';
		$c .= "<td>Owner:</td><td class='faux_edit'>".$this->get_user_name($lt['order_by'])."</td>";
		$c .= '</td></tr></table>';
		//==== End Load Module ====
		$c .= "</tr><tr>";
		//==== edit warehouse ====
		$c .= "<!-- edit warehouse start -->";
		$c .= '<td>'.$wt->edit_warehouse().'</td>';
		$c .= "<!-- edit warehouse end -->";
		//=====================
		//$c .= '<td>';
		//==== Right Column ====
		
		
		
		
		//==== Product Info ====
		/*$c .= '<table style="border:1px solid black"><tr>';
		$c .= '<th colspan=4>Product Info</td>';
		$c .= '</tr><tr>';
		$c .= '</td></tr></table>';
		//==== End Product Info ====
		
		//==== Driver Info ====
		$c .= '<table style="border:1px solid black"><tr>';
		$c .= '<th colspan=4>Driver Info</td>';
		$c .= '</tr><tr>';
		$c .= '</td></tr></table>';*/
		//==== End Product Info ====
		
		//==== End Right Column ====
		$c .= '</td>';
		$c .= '</tr></table>';
		$c .= "<input type='button' onclick='window.close();' value='Close'>";
		$c .= '<!-- get_load_warehouse_edit end -->';
		return $c;
	}
	
	function get_load_warehouse($load_id, $warehouse_id)
	{
		$sql ="	SELECT	*
			, IFNULL(DATE_FORMAT(close_time, '$this->time_format'), CASE DAYNAME(activity_date)
				WHEN 'Sunday' THEN DATE_FORMAT(sun_close_time, '$this->time_format')
				WHEN 'Monday' THEN DATE_FORMAT(mon_close_time, '$this->time_format')
				WHEN 'Tuesday' THEN DATE_FORMAT(tues_close_time, '$this->time_format')
				WHEN 'Wednesday' THEN DATE_FORMAT(wed_close_time, '$this->time_format')
				WHEN 'Thursday' THEN DATE_FORMAT(thurs_close_time, '$this->time_format')
				WHEN 'Friday' THEN DATE_FORMAT(fri_close_time, '$this->time_format')
				WHEN 'Saturday' THEN DATE_FORMAT(sat_close_time, '$this->time_format')
				ELSE 'N/A'
			END) close_time
			,IFNULL(DATE_FORMAT(open_time, '$this->time_format'), CASE DAYNAME(activity_date)
				WHEN 'Sunday' THEN DATE_FORMAT(sun_open_time, '$this->time_format')
				WHEN 'Monday' THEN DATE_FORMAT(mon_open_time, '$this->time_format')
				WHEN 'Tuesday' THEN DATE_FORMAT(tues_open_time, '$this->time_format')
				WHEN 'Wednesday' THEN DATE_FORMAT(wed_open_time, '$this->time_format')
				WHEN 'Thursday' THEN DATE_FORMAT(thurs_open_time, '$this->time_format')
				WHEN 'Friday' THEN DATE_FORMAT(fri_open_time, '$this->time_format')
				WHEN 'Saturday' THEN DATE_FORMAT(sat_open_time, '$this->time_format')
				ELSE 'N/A'
			END) open_time
			,DATE_FORMAT(creation_date, '$this->date_format $this->time_format') creation_date
			,DAYNAME(activity_date) day_name
			,DATE_FORMAT(activity_date, '$this->date_format') activity_date
			FROM load_warehouse lw, warehouse w
			WHERE lw.warehouse_id = $warehouse_id
			AND load_id = $load_id
			AND w.warehouse_id = lw.warehouse_id";
		$r = DB::query($sql);
		return DB::fetch_assoc($r);
	}
	
	function get_load_warehouse_old($load_id, $warehouse_id)
	{
		$sql ="	SELECT *
					, DATE_FORMAT(creation_date, '$this->date_format $this->time_format') creation_date
					, DATE_FORMAT(activity_date, '$this->date_format') activity_date
					, DATE_FORMAT(open_time, '$this->time_format') open_time
					, DATE_FORMAT(close_time, '$this->time_format') close_time
				FROM load_warehouse
				WHERE load_id = $load_id
				AND warehouse_id = $warehouse_id";
		$r = db_query($sql);
		return db_fetch_assoc($r);
	}
	
	function fetch_edit($name, $value=null)
	{
		$c =& $this->get_column($name);
		$pk_obj =& $this->get_primary_key();
		$pk_name = $pk_obj->get_name();
		//echo $value."<br>";
		if(isset($value))
		{
			$o = $c->get_edit_html($value);
			
		}else{
			$o = $c->get_edit_html();
		}
		if(isset($_REQUEST[$pk_name]))
		{
			$o->set_id("action=$this->update&table=$this->name&warehouse_id=$this->warehouse_id&load_id=$this->load_id&".$name."=");
			$o->add_attribute('onchange', 'db_save(this);column_updated(this);');
		$script = "<script>
		var i = document.getElementById('$name');
		i.setReturnFunction(onchange);
		</script>";
		}
		
		
		return $o->render();
	}
	
	function get_warehouse_type_select()
	{
		require_once("includes/select_input.php");
		
		$select = new select_input("type", null, null, $this->warehouse_types);
		$select->set_id("warehouse_type");

		return $select->render();
	}
}
?>