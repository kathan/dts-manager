<?php
require_once "dts_table.php";
class customer_table extends dts_table
{
	var $customer_id;
	function customer_table()
	{
		
		$this->dts_table("customer");
		if(isset($_REQUEST['customer_id']))
		{
			$this->customer_id = $_REQUEST['customer_id'];
			$this->current_row();
		}
		
		$this->prefix='T';
		require_once"includes/hidden_input.php";
		$i = new hidden_input('page', 'customer');
		$this->add_other_inputs($i);
		
		$this->hide_delete();
		$this->hide_column('customer_id');
		$this->add_table_params('page', 'customer');
		
		require_once"includes/hidden_input.php";
		$i = new hidden_input('page', 'customer');
		$this->add_other_inputs($i);
		
		$c =& $this->get_column('account_status');
		$c->set_value_list($this->account_status_list);
		
		$c =& $this->get_column('acct_owner');
		$c->set_value_list($this->get_users());
		//$c->set_parent_label_column('username');
		
		//$c =& $this->get_column('lead_source');
		//$c->set_parent_label_column('username');
				
		$this->tab_menu->add_tab("http://".HTTP_ROOT."/?page=customer&action=$this->search_edit", 'Search');
		$this->tab_menu->add_tab("http://".HTTP_ROOT."/?page=customer", 'List');
		//$this->tab_menu->add_tab("http://".HTTP_ROOT."/?page=customer&action=$this->new_str", 'New');
		
	}
	
	function render()
	{
		$code ='';
		if(logged_in())
		{
			
			if(isset($_REQUEST[$this->portal]))
			{
				switch(safe_get($_REQUEST[$this->portal]))
				{
					
					case $this->customer_notes:
						$code .= $this->get_notes();
						break;
					case $this->load:
						$code .= $this->get_loads();
						break;
					case $this->warehouse:
						$code .= $this->get_warehouses();
						break;
				}			
			}else{
				$code .= "<title>".SITE_NAME."-Customers</title>";
				$code .= $this->portal_script();
				$code .= "<head><script src='sortable.js'></script>
				<script src='db_save.js.php'></script>
				<SCRIPT LANGUAGE=\"JavaScript\">
			
				function popUp(URL)
				{
					day = new Date();
					id = day.getTime();
					eval(\"page\" + id + \" = window.open(URL, '\" + id + \"', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=600,height=600,left = 640,top = 225');\");
				}
				</script>";
			
				//include_once("includes/portal_style.php");
								
				//$code .= $this->get_customer_menu();
				$code .= $this->tab_menu->render();
				$code .= "<div class='tab_sep'></div>";
				$code .= "<div class='content load_content'>";
				
				switch(get_action())
				{
					case $this->wh_to_cust:
						$this->add_as_customer();
						header("location: http://".HTTP_ROOT."/?page=customer&action=$this->edit_str&customer_id=$this->customer_id&$this->new_str");
						break;
					case $this->add_str:
						if($this->add())
						{
							//$code .= $this->get_customers();
							$code .= $this->get_search_results();
						}else{
							$code .= $this->error_str;
							$code .= $this->feedback;
							$code .= $this->get_edit();
						}
						break;
					case $this->delete_str:
						$this->delete();
						//$code .= '<center><h2>Customer List</h2>';
						//$code .= $this->get_customers();
						$code .= $this->get_search_results();
						break;
					case $this->search_edit:
						
						$code .= $this->get_search();
						break;
					case $this->search:
						//$code .= '<center><h2>Customer Search Results</h2>';
						$code .= $this->get_search_results();
						break;
					case $this->edit_str:
						//$code .= '<center><h2>Customer Edit</h2>';
						$code .= $this->get_edit();
						break;
					case $this->new_str:
						$code .= '<center><h2>New Customer</h2>';
						$this->create_new();
						header("location: http://".HTTP_ROOT."/?page=customer&action=$this->edit_str&customer_id=$this->customer_id&$this->new_str");
						//$code .= $this->_render_edit();
						break;
					default:
						//$code .= '<center><h2>Customer List</h2>';
						//$code .= $this->get_customers();
						$code .= $this->get_search_results();
						break;
				}
				$code .= "</div>";
			}
		}
		$feedback='';
		if($this->error())
		{
			$feedback = $this->error();
		}
		return $feedback.$code;
	}
	function create_new()
	{
		
		set_post('acct_owner', get_user_ID());
		$this->add();
		$this->customer_id = $this->last_id;
	}
	function get_customers()
	{
		require_once"includes/portal.php";
		$p = new portal("	SELECT	customer_id, CONCAT('$this->prefix', customer_id) id, name customer_name, city, state, (SELECT username FROM users u WHERE u.user_id = c.acct_owner) acct_owner
							FROM customer c
							");
		
		$p->set_row_action("\"row_clicked('\$id', '\$pk', '\$table')\";");
		$p->set_primary_key('customer_id');
		$p->hide_column('customer_id');
		$p->set_table('customer');
		return $p->render();
	}
	
	function get_loads()
	{
		require_once"includes/portal.php";
		$p = new portal("SELECT load_id,
								DATE_FORMAT(l.activity_date,'$this->date_format') activity_date,
								IFNULL((SELECT CONCAT(IF(cancelled,'<span style=\"background-color:$this->cancel_color\">',IF(rating='Expedited','<span style=\"background-color:$this->expedited_color\">','')),w.city, ', ', w.state) FROM load_warehouse lw, warehouse w WHERE lw.load_id = l.load_id AND lw.type = 'PICK' AND w.warehouse_id = lw.warehouse_id limit 1), '$this->null_str') origin,
								IFNULL((SELECT CONCAT(IF(cancelled,'<span style=\"background-color:$this->cancel_color\">',IF(rating='Expedited','<span style=\"background-color:$this->expedited_color\">','')),w.city,', ', w.state) FROM load_warehouse lw, warehouse w WHERE lw.load_id = l.load_id AND lw.type = 'DEST' AND w.warehouse_id = lw.warehouse_id limit 1), '$this->null_str') dest
								FROM `load` l WHERE l.customer_id = $this->customer_id");
		$p->hide_column('load_id');
		$p->set_table('load');
		$p->set_primary_key('load_id');
		$p->set_row_action("\"row_clicked('\$id', '\$pk', '\$table')\";");
		$c .= $p->render();
		
		return $c;
	}
	
	function get_warehouses()
	{
		echo $_REQUEST['customer_id'];
		require_once"includes/portal.php";
		$p = new portal("	SELECT warehouse_id, name, city, state
							FROM warehouse w
							WHERE w.customer_id = $this->customer_id");
		$p->set_table('warehouse');
		$p->hide_column('warehouse_id');
		$p->set_primary_key('warehouse_id');
		return $p->render();
	}
	
	function get_notes()
	{
		require_once"includes/portal.php";
		//echo $_SERVER['REQUEST_URI'];
		$p = new portal("SELECT note_id, notes, last_updated
								FROM customer_notes cn WHERE cn.customer_id = $this->customer_id
								ORDER BY last_updated");
		$p->set_table('customer_notes');
		$p->hide_column('note_id');
		$p->set_primary_key('note_id');
		return $p->render();
	}
	function get_search()
	{
		require_once("includes/submit_input.php");
		
		$c = '<center><h2>Customer Search</h2>';
		//$c .= "Use % as a wildcard character";
		
		$si = new submit_input($this->search, $this->action);
		$f =& $this->get_form();
		$f->set_get();
		$this->omit_all_columns();
		$this->unhide_column('customer_id');
		$this->add_virtual_column('customer_id', 'customer_id');
		$this->add_virtual_column('name', 'name');
		$this->add_virtual_column('city', 'city');
		$this->add_virtual_column('state', 'state');
		$s = new select_input('acct_owner', 'user_id', 'username', $this->get_users());
		$f = $this->get_form();
		$f->add_input($s);
		$this->set_submit_input($si);
		$c .= $this->_render_edit();
		return $c;
	}
	
	function get_search_results()
	{
		require_once("includes/portal.php");
		$c ='';
		
		$c .= '<center><h2>Customer Search Results</h2>';
		
		
		$sql = "SELECT CONCAT('T', customer_id) id, customer_id, name, address, city, state FROM customer ";
		$clause = 'WHERE';
		$where='';
		if(isset($_REQUEST['customer_id']) && intval(trim($_REQUEST['customer_id'], 't T')) > 0)
		{
			$where .= " $clause customer_id = ".intval(trim($_REQUEST['customer_id'], 't T'));
		}else{
			if(isset($_REQUEST['name']) && $_REQUEST['name'] != '')
			{
				$where .= " $clause name like '%$_REQUEST[name]%'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['address']) && $_REQUEST['address'] !='')
			{
				$where .= " $clause address like '%$_REQUEST[address]%'";
			}
			if(isset($_REQUEST['city']) && $_REQUEST['city'] !='')
			{
				$where .= " $clause city like '%$_REQUEST[city]%'";
			}
			if(isset($_REQUEST['state']) && $_REQUEST['state'] !='')
			{
				$where .= " $clause state like '%$_REQUEST[state]%'";
			}
			if(isset($_REQUEST['acct_owner']) && $_REQUEST['acct_owner'] !='')
			{
				$where .= " $clause acct_owner = $_REQUEST[acct_owner]";
			}
		}
		$sql .= $where;
		//echo $sql;
		$p = new portal($sql);
		$p->hide_column('customer_id');
		$p->set_primary_key('customer_id');
		$p->set_row_action("\"row_clicked('\$id', '\$pk', '\customer')\";");
		$c .= $p->render();
		
		return $c;
	}
	function get_search_results_old($callback=null)
	{
		$this->omit_all_columns();
		if(isset($callback))
		{
			$this->add_virtual_column("'<input type=\"button\" onclick=\"$callback\" value=\"Select\">'", 'Select');
		}
		$this->add_virtual_column('name', 'name');
		$this->add_virtual_column('city', 'city');
		$this->add_virtual_column('state', 'state');
		$this->wildcard_search=true;
		return $this->_render_list();
	}
	function current_row()
	{
		if(!isset($this->current_row))
		{
			$this->current_row = $this->get_row($this->customer_id);
		}
		return $this->current_row;
	}
	function get_edit()
	{
		
		$c = '';
		
		$r = $this->current_row();
		$c .= "<center><h2>$this->prefix$r[customer_id]</h2></center>";
		$c .= "<form method='post'>
					<input type='hidden' name='action' value='$this->cust_to_wh'>
					<input type='hidden' name='page' value='warehouse'>
					<input type='hidden' name='customer_id' value='$this->customer_id'>
					<input type='submit' value='Add As Warehouse'>
				</form>";
		//====Main====
		$c .= "<table width='100%'><tr><td>";
		//============
		$c .= "<fieldset><legend>Main</legend><table>";
	
		//====Customer Name====
		//$c .= "<tr><td>Customer ID:</td><td>$this->prefix$r[customer_id]</td></tr>";
		$c .= '<tr><td>Name:</td><td>'.$this->fetch_edit('name', $r['name']).'</td></tr>';
		//=====================
		
		//====Customer Address====
		$c .= '<tr><td>Address:</td><td>'.$this->fetch_edit('address', $r['address']).'</td></tr>';
		//=====================
		
		//====Customer City State Zip====
		$c .= '<tr><td>City/State/Zip:</td><td>'.$this->fetch_edit('city', $r['city'])
					.'</td><td>'.$this->fetch_edit('state', $r['state'], $r['customer_id'])
					.'</td><td>'.$this->fetch_edit('zip', $r['zip']).'</td></tr>';
		//=====================
		
		//====Phone Fax====
		$c .= '<tr><td>Phone/Fax:</td><td>'.$this->fetch_edit('phone', $r['phone'])
					.'</td><td colspan=2>'.$this->fetch_edit('fax', $r['fax']).'</td></tr>';
		//=====================
		
		//====Contact Name====
		$c .= '<tr><td>Contact&nbsp;Name:</td><td>'.$this->fetch_edit('contact_name', $r['contact_name']).'</td></tr>';
		//=====================
	
		//====Contact Email====
		$c .= '<tr><td>Email:</td><td>'.$this->fetch_edit('email', $r['email']).'</td></tr>';
		//=====================
		
		$c .= "</table></fieldset>";
		//=====================
		//=====================
		
		//====End Main====
		
		//====Warehouses====
		
		/*$c .= "<fieldset><legend>Warehouse List</legend>";
		
		$c .= "<div id='warehouse'>
				</div>
				<script>
					get_portal('warehouse');
				</script>";
		$c .= "</fieldset>";
		$c .= $this->style("
		#warehouse .tableContainer{height:99% !important;}
		#warehouse table>tbody{height:10em !important;}");*/
		//==================
		//====Notes====
		$c .= "<fieldset><legend>Notes</legend>";
		$c .= "<form>
	<input type=button value=\"New\" onClick=\"javascript:popUp('http://".HTTP_ROOT."/?page=$this->customer_notes&action=$this->new_str&customer_id=$this->customer_id&".SMALL_VIEW."')\">
	</form>";
		$c .= "<div id='customer_notes'>
				</div>
				<script>
					get_portal('customer_notes');
				</script>";
		$c .= "</fieldset>";
		$c .= $this->style("
		#customer_notes .tableContainer{height:99% !important;}
		#customer_notes table>tbody{height:10em !important;}");
		//=============
		$c .= "</td><td valign='top'>";
		//====Billing====
		//===============
		$c .= "<fieldset><legend>Billing</legend><table>";
		if($r['billing_address'] == '' && $r['billing_city'] == '' && $r['billing_state'] == '' && $r['billing_zip'] == '' && $r['billing_phone'] == '' && $r['billing_fax'] == '' && $r['billing_contact_name'] == '')
		{
			$c .= "<tr><td>Same as main:<input type=checkbox onchange='same_address(this)'></td></tr>
		".$this->script('
		function same_address(cb)
		{
			if(cb.checked)
			{
			var a = document.getElementsByName(\'billing_address\');
			var b = document.getElementsByName(\'address\');
			a[0].value = b[0].value;
			//db_save(a[0].id, a[0].value);
			db_save(a[0]);
			
			var a = document.getElementsByName(\'billing_city\');
			var b = document.getElementsByName(\'city\');
			a[0].value = b[0].value;
			//db_save(a[0].id, a[0].value);
			db_save(a[0]);
			
			var a = document.getElementsByName(\'billing_state\');
			var b = document.getElementsByName(\'state\');
			a[0].value = b[0].value;
			//db_save(a[0].id, a[0].value);
			db_save(a[0]);
			
			var a = document.getElementsByName(\'billing_zip\');
			var b = document.getElementsByName(\'zip\');
			a[0].value = b[0].value;
			//db_save(a[0].id, a[0].value);
			db_save(a[0]);
			
			var a = document.getElementsByName(\'billing_phone\');
			var b = document.getElementsByName(\'phone\');
			a[0].value = b[0].value;
			//db_save(a[0].id, a[0].value);
			db_save(a[0]);
			
			var a = document.getElementsByName(\'billing_fax\');
			var b = document.getElementsByName(\'fax\');
			a[0].value = b[0].value;
			//db_save(a[0].id, a[0].value);
			db_save(a[0]);
			
			var a = document.getElementsByName(\'billing_contact_name\');
			var b = document.getElementsByName(\'contact_name\');
			a[0].value = b[0].value;
			//db_save(a[0].id, a[0].value);
			db_save(a[0]);
			}
		}');
		}
		//====Billing Name====
		$c .= '<tr><td>Attention:</td><td>'.$this->fetch_edit('billing_attention', $r['billing_attention']).'</td></tr>';
		//=====================
		
		//====Billing Address====
		$c .= '<tr><td>Address:</td><td>'.$this->fetch_edit('billing_address', $r['billing_address']).'</td></tr>';
		//=====================
		
		//====Billing City State Zip====
		$c .= '<tr><td>City/State/Zip:</td><td>'.$this->fetch_edit('billing_city', $r['billing_city'], $r['customer_id'])
					.'</td><td>'.$this->fetch_edit('billing_state', $r['billing_state'], $r['customer_id'])
					.'</td><td>'.$this->fetch_edit('billing_zip', $r['billing_zip']).'</td></tr>';
		//=====================
		
		//====Billing Fax====
		$c .= '<tr><td>Phone/Fax:</td><td>'.$this->fetch_edit('billing_phone', $r['billing_phone'], $r['customer_id'])
					.'</td><td colspan=2>'.$this->fetch_edit('billing_fax', $r['billing_fax']).'</td></tr>';
		//=====================
		
		//====Billing Name====
		$c .= '<tr><td>Contact&nbsp;Name:</td><td>'.$this->fetch_edit('billing_contact_name', $r['billing_contact_name']).'</td></tr>';
		//=====================
	
		//====Billing Email====
		//$c .= '<tr><td>Email:</td><td>'.$this->fetch_edit('billing_email', $r['billing_email']).'</td></tr>';
		//=====================
		
		$c .= "</table></fieldset>";
		//=====================
		//=====================
		
		//====Misc====
		$c .= "<fieldset><table>";
		
		if(logged_in_as('admin'))
		{
			$c .= '<tr><td>Account Status:</td><td>'.$this->fetch_edit('account_status', $r['account_status']).'</td>';
		}else{
			$c .= "<tr><td>Account Status:</td><td class='faux_edit'>$r[account_status]</td>";
		}
		$c .= '<td>Account Owner:</td><td>'.$this->fetch_edit('acct_owner', $r['acct_owner']).'</td></tr>';
		$c .= "</table></fieldset>";
		//=============
		
		
		//====Loads====
		$c .= "<fieldset><legend>Loads</legend>";

		$c .= "<div id='$this->load'>
				</div>
				<script>
					get_portal('$this->load', '');
				</script>";
		$c .= "</fieldset>";
		
		$c .= $this->style("
		#load .tableContainer{height:12em !important;}
		#load table>tbody{height:9em !important;}");
		//=============
		$c .= "</td></tr></table>";
		if(isset($_REQUEST[$this->new_str]))
		{
			$c .= "<input type='button' onclick='cancel();' value='Cancel'>";
			$c .= "<input type='button' onclick='window.location = \"http://".HTTP_ROOT."/?page=$this->name\";' value='Save'>";
			$c .= $this->script("
				
						function cancel()
						{
							if(confirm('Are you sure you want to cancel?'))
							{
								window.location=\"http://".HTTP_ROOT."/?action=$this->delete_str&page=$this->name&customer_id=$this->customer_id\";
							}
						}");
		}
		return $c;
	}
	
	function get_customer_menu()
	{
		$c = "<table><tr>";
		$c .= "<td><a href='http://".HTTP_ROOT."/?page=customer'><div class='menu'>All Accounts</div></a></td>";
		$c .= "<td><a href='http://".HTTP_ROOT."/?page=customer&action=$this->search_edit'><div class='menu'>Search</div></a></td>";
		$c .= "<td><a href='http://".HTTP_ROOT."/?page=customer&action=$this->new_str'><div class='menu'>New</div></a></td>";
		$c .= $this->back_button();
		$c .= "</tr></table>";
		return $c;
	}
	
	function fetch_edit($name, $value)
	{
		$c =& $this->get_column($name);
		$pk_obj =& $this->get_primary_key();
		$pk_name = $pk_obj->get_name();
		
		$o = $c->get_edit_html($value);
		$o->set_id("action=update&table=$_REQUEST[page]&".$pk_name."=".$_REQUEST[$pk_name]."&".$name."=");
		//$o->add_attribute('onchange', 'db_save(this.id, this.value);');
		$o->add_attribute('onchange', 'db_save(this);');
		$script = "
		<script>
			var i = document.getElementById('$name');
			i.setReturnFunction(onchange);
		</script>";
		
		return $o->render();
	}
	function portal_script()
	{
		return "
			<script>
			function get_portal(table)
			{
				var d = document.getElementById(table);
				d.innerHTML = 'Loading '+table;
				var portal = getFromURL('http://".HTTP_ROOT."/?page=$this->name&portal='+table+'&customer_id=$this->customer_id&action=portal&".SMALL_VIEW."');
				d.innerHTML = '';
				d.innerHTML = portal;
			}
			</script>";
			
	}
	
	function add_as_customer()
	{
		$sql = "INSERT INTO customer(name, address, city, state, zip, phone, fax, contact_name, acct_owner)
				SELECT name, address, city, state, zip, phone, fax, contact_name, ".get_user_id()."
				FROM warehouse
				WHERE warehouse_id = $_REQUEST[warehouse_id]";
		$r = db_query($sql);
		if(db_error())
		{
			echo db_error();
		}
		$this->customer_id = db_insertid();
		$this->current_row();
	}
}
?>