<?php
echo "<title>".SITE_NAME."-Load-Carrier</title>";
require_once"includes/global.php";
require_once"includes/auth.php";
require_once"includes/dts_table.php";
class load_carrier_table extends dts_table
{
	var $carrier_id;
	var $load_id;
	var $current_row;
	function load_carrier_table()
	{
		$this->dts_table("load_carrier");
	
		$this->add_table_params('page', 'load_carrier');
	
		require_once"includes/hidden_input.php";
		$i = new hidden_input('page', 'load_carrier');
		$this->add_other_inputs($i);
	
		$c =& $this->get_column('carrier_id');
		$c->set_parent_label_column('name');
	
		$c =& $this->get_column('booked_with');
		$c->set_parent_label_column('username');
	}
	
	function render()
	{
		if(isset($_REQUEST['load_id']))
		{
			//echo $_REQUEST['load_id'];
			$this->load_id = $_REQUEST['load_id'];
		}
		
		if(isset($_REQUEST['carrier_id']))
		{
			//echo $_REQUEST['load_id'];
			$this->carrier_id = $_REQUEST['carrier_id'];
		}
		$code = "<title>".SITE_NAME."-Loads</title>";
		$code .= $this->db_script();
		$code .= $this->portal_script();
		$code .= $this->module_script();
		$code .= $this->sortable_script();
		$code .= $this->popup_script();
		$code .= '<link rel="stylesheet" href="'.APP_ROOT.'/style.css" type="text/css" media="all">';
		$code .= "<div class='content load_content' id='content'>";
		if(logged_in())//1
		{
			switch(get_action())
			{
				case $this->edit_str:
					$code .= $this->get_load_carrier_edit();
					break;
			}
		}
		$code .= "</div>";
		return $code;
	}
	
	function fetch_edit($name, $value=null)
	{
		$c =& $this->get_column($name);
		$pk_obj =& $this->get_primary_key();
		$pk_name = $pk_obj->get_name();
		
		if(isset($value))
		{
			$o = $c->get_edit_html($value);
			
		}else{
			$o = $c->get_edit_html();
		}
		if(isset($_REQUEST[$pk_name]))
		{
			$o->set_id("action=$this->update&table=$this->name&carrier_id=$this->carrier_id&load_id=$this->load_id&".$name."=");
			$o->add_attribute('onchange', 'db_save(this.id, this.value);column_updated(this);');
		$script = "<script>
		var i = document.getElementById('$name');
		i.setReturnFunction(onchange);
		</script>";
		}
		
		
		return $o->render();
	}
	function current_row()
	{
		if(isset($this->current_row))
		{
			return $this->current_row;
		}else{
			if(!isset($this->carrier_id) && isset($_REQUEST['carrier_id']))
			{
				$this->carrier_id = $_REQUEST['carrier_id'];
			}
			$this->current_row = $this->get_lc_row();
			return $this->current_row;
		}
	}
	function get_load_carrier_edit()
	{
		$c='';
		$r = $this->current_row();
		//$c .= $this->check_size();
		$c .= $this->error_str;
		$c .= "<html><body>";
		$c .= "<table width='100%'><tr>";
		$c .= "<td class='bold'>Load ID: $this->load_id</td>";
		//$c .= "<td>Carrrier ID: $this->carrier_id</td>";
		$c .= "<td class='bold'>Order By<br>$r[order_by]</td>";
		$c .= "<td class='bold'>Activity Date<br>$r[order_date]</td>";
		$c .= "<table><tr>";
		//==== Carrier ====
		$c .= "<td width='50%'><fieldset style=''><legend>Carrier</legend>";
		$c .='<table><tr>';
		$c .= "<td>S$r[carrier_id]</td>";
		$c .= "<td>".$this->nbsp($r['name'])."<br>
					".$this->nbsp($r['main_phone_number'])."<br>
					".$this->nbsp($r['fax'])."</td>";
		$c .= "<td>".$this->nbsp($r['phys_address'])."<br>
					".$this->nbsp($r['phys_city'])."&nbsp;
					".$this->nbsp($r['phys_state'])."&nbsp;
					".$this->nbsp($r['phys_zip'])."<br>&nbsp;</td>";
		$c .='</tr></table>';
		$c .= "</fieldset></td>";
		//================
		//==== Booking ====
		$c .= "<td ><fieldset style=''><legend>Booking Information</legend>";
		$c .='<table><tr>';
		$c .= "<td>Booked&nbsp;With:</td><td class='faux_edit'>$r[booked_with]</td>";
		//$c .= "<td>Booked Salesperson:</td><td class='faux_edit'>$r[booked_salesperson]</td>";
		$n =& $this->get_column('notes');
		$o =& $n->get_edit_html();
		$o->set_cols(30);
		//$o->set_rows(5);
		$c .= "</tr><tr>";
		$c .= "<td>Notes:</td><td colspan=3>".$this->fetch_edit('notes',$r['notes'])."</td>";
		$c .='</tr></table>';
		$c .= "</fieldset></td>";
		//================
		$c .= "</tr><tr>";
		//==== Driver ====
		$c .= "<td colspan=2><fieldset style=''><legend>Driver</legend>";
		$c .='<table><tr>';
		$c .= "<td>Driver Name:</td><td>".$this->fetch_edit('driver_name',$r['driver_name'])."</td>";
		$c .= "<td>Cell:</td><td>".$this->fetch_edit('cell_number',$r['cell_number'])."</td>";
		$c .= "</tr><tr>";
		$c .= "<td>Tractor Number:</td><td>".$this->fetch_edit('tractor_number',$r['tractor_number'])."</td>";
		$c .= "<td>Trailer Number:</td><td>".$this->fetch_edit('trailer_number',$r['trailer_number'])."</td>";
		$c .= "</tr><tr>";
		$c .= "<td>Equipment Type:</td><td>".$this->fetch_edit('equipment_type',$r['equipment_type'])."</td>";
		$c .='</tr></table>';
		$c .= "</fieldset></td>";
		//================
		
		
		$c .= "</tr><tr>";
		//==== Money ====
		/*line_haul   	decimal(11,2)  	   	   	Yes   	   	   	  Change   	  Drop   	  Primary   	  Index   	  Unique   	 Fulltext
	 detention  	decimal(11,2) 	  	  	Yes  	  	  	Change 	Drop 	Primary 	Index 	Unique 	Fulltext
	 tonu  	decimal(11,2) 	  	  	Yes  	  	  	Change 	Drop 	Primary 	Index 	Unique 	Fulltext
	 unload_load  	decimal(11,2) 	  	  	Yes  	  	  	Change 	Drop 	Primary 	Index 	Unique 	Fulltext
	 fuel  	decimal(11,2) 	  	  	Yes  	  	  	Change 	Drop 	Primary 	Index 	Unique 	Fulltext
	 other*/
		$c .= "<td colspan=2>";
		/*$c .= "<fieldset style=''><legend>Money</legend>";
		$c .='<table><tr>';
		$c .= "<td>Line Haul:</td><td>".$this->fetch_edit('line_haul',$r['line_haul'])."</td>";
		$c .= "<td>Detention:</td><td>".$this->fetch_edit('detention',$r['detention'])."</td>";
		$c .= "</tr><tr>";
		$c .= "<td>TONU:</td><td>".$this->fetch_edit('tonu',$r['tonu'])."</td>";
		$c .= "<td>Unload/Load:</td><td>".$this->fetch_edit('unload_load',$r['unload_load'])."</td>";
		$c .= "</tr><tr>";
		$c .= "<td>Fuel:</td><td>".$this->fetch_edit('fuel',$r['fuel'])."</td>";
		$c .= "<td>Other:</td><td>".$this->fetch_edit('other',$r['other'])."</td>";
		$c .='</tr></table>';
		$c .= "</fieldset>";
		$c .= "</td>";*/
		//================
		$c .='</tr></table>';
		$c .= "<input type='button' value='Close' onclick='window.close();'>";
		$c .= $this->script('function column_updated(){}');
		$c .= "</body></html>";
		return $c;
	}
	
	function get_lc_row()
	{
		//needs to be updated for multiple column primary keys
		$sql = "select *, (SELECT username FROM users u WHERE u.user_id = l.order_by) order_by, DATE_FORMAT(order_date, '$this->date_format') order_date, (SELECT username FROM users u WHERE u.user_id = lc.booked_with) booked_with
				FROM `load_carrier` lc, carrier c, `load` l
				WHERE lc.load_id = $this->load_id
				AND lc.carrier_id = $this->carrier_id
				AND c.carrier_id = lc.carrier_id
				AND l.load_id = lc.load_id";
				//echo $sql;
		$this->resource = db_query($sql);
		if(db_error())
		{
			$this->add_error(db_error());
			$this->add_error($sql);
			$this->add_error('load_carrier_table.get_row()');
			echo $this->error_str;
		}else{
			return db_fetch_assoc($this->resource);
			echo "boo";
		}
	}
}
?>