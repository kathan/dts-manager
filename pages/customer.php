<?php
//ini_set('display_errors', 'On');//Debug only
require_once('includes/app.php');
require_once('includes/DB.php');
require_once('includes/auth.php');
require_once('includes/global.php');
require_once('includes/dts_table.php');
require_once('includes/hidden_input.php');
require_once('includes/portal.php');
require_once('includes/Template.php');
require_once('includes/submit_input.php');
require_once('includes/Paginator.php');

class customer_table extends dts_table
{
	var $customer_id;
	function customer_table()
	{
		
		$this->dts_table("customer");
		$this->page = $_GET['page'];
		if(isset($_REQUEST['customer_id']))
		{
			$this->customer_id = $_REQUEST['customer_id'];
			$this->current_row();
		}
		
		$this->prefix='T';
		
		$i = new hidden_input('page', 'customer');
		$this->add_other_inputs($i);
		
		$this->hide_delete();
		$this->hide_column('customer_id');
		$this->add_table_params('page', 'customer');
		
		
		$i = new hidden_input('page', 'customer');
		$this->add_other_inputs($i);
		
		$c =& $this->get_column('account_status');
		$c->set_value_list($this->account_status_list);
		
		$c =& $this->get_column('acct_owner');
		$c->set_value_list($this->get_users());
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
				$GLOBALS['page_title'] = 'Customers';
				
				switch(get_action())
				{
					case $this->wh_to_cust:
						$this->add_as_customer();
						header("location: ?page=$this->page&action=$this->edit_str&customer_id=$this->customer_id&$this->new_str");
						break;
					case $this->add_str:
						if($this->add())
						{			
							$code .= $this->show_search_results($this->get_search_results());
						}else{
							$code .= $this->error_str;
							$code .= $this->feedback;
							$code .= $this->get_edit();
						}
						break;
					case $this->delete_str:
						$this->delete();
						
						$code .= $this->show_search_results($this->get_search_results());
						break;
					case $this->search_edit:
						
						$code .= $this->get_search();
						break;
					case $this->search:
						
						$code .= $this->show_search_results($this->get_search_results());
						break;
					case $this->edit_str:
						
						$code .= $this->get_edit();
						break;
					case $this->new_str:
						if($this->add())
						{
							$this->customer_id = $this->last_id;
							$code .= $this->get_edit();
						}else{
							$code .= $this->error_str;
						}
						break;
					case $this->print:
						if(logged_in_as('admin'))
						{
							$code .= $this->show_cust_print($this->get_search_results());
						}else{
							$code .= "No access.";
						}
						break;
					default:
						
						$code .= $this->show_search_results($this->get_search_results());
						break;
				}
				if(!isset($_GET['sml_view']))
				{
					//$code .= "<title>".SITE_NAME."-Customers</title>";
					$code .= $this->portal_script();
					$code .= "<head><script src='sortable.js'></script>
							<script src='db_save.js.php'></script>
							<script type=\"text/javascript\">
			
							function popUp(URL)
							{
								day = new Date();
								id = day.getTime();
								eval(\"page\" + id + \" = window.open(URL, '\" + id + \"', 		'toolbar=0,location=0,statusbar=0,menubar=0,resizable=1,width=600,height=600,left = 640,top = 225');\");
							}
							</script>";
			
					//$code .= "<div class='tab_sep'></div>";
					$code .= "<div class='content load_content'>";
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
	
	function check_for_existing($cust)
	{
		$sql = "SELECT c.*, '$cust[address]' REGEXP '^[0-9]+', address REGEXP '^[0-9]+', soundex(address) 
				FROM customer c
				WHERE (soundex(name) like CONCAT(soundex('$cust[name]'), '%')
				OR soundex('$cust[name]') like CONCAT(soundex(name), '%'))
				AND (soundex(address) like CONCAT(soundex('$cust[address]'), '%')
				OR soundex('$cust[address]') like CONCAT(soundex(address), '%'))
				AND (zip like '$cust[zip]%'
				OR '$cust[zip]' like CONCAT(zip, '%'))";
		$re = DB::query($sql);
		return $re;
	}
	
	function create_new()
	{
		
		set_post('acct_owner', get_user_ID());
		$this->add();
		$this->customer_id = $this->last_id;
	}
	function get_customers()
	{
		
		$sql = "	SELECT	customer_id, CONCAT('$this->prefix', customer_id) id, name customer_name, city, state, (SELECT username FROM users u WHERE u.user_id = c.acct_owner) acct_owner
							FROM customer c
							";
		if(!logged_in_as('admin') && !logged_in_as('super admin')){
			$sql .= " WHERE acct_owner = ".get_user_id();
		}
		$p = new portal($sql);
		$p->set_row_action("\"row_clicked('\$id', '\$pk', '\$table')\";");
		$p->set_primary_key('customer_id');
		$p->hide_column('customer_id');
		$p->set_table('customer');
		return $p->render();
	}
	
	function get_loads_old()
	{
		if($this->customer_id>0){
			
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
		}
		return $c;
	}
	
	function get_loads(){
	//Added 10/15/13
		if($this->customer_id>0){
			$sql = "SELECT 	load_id
							, activity_date
							, load_type
							, cancelled
							, rating
							, RIGHT(l.load_id, 4) 'id'
							, IFNULL(
										(SELECT CONCAT(w.state,' ',w.city)
										FROM warehouse w, load_warehouse lw
										WHERE w.warehouse_id = lw.warehouse_id
										AND lw.load_id = l.load_id
										AND lw.type = 'PICK'
										ORDER BY lw.activity_date ASC
										LIMIT 1) 
								, '$this->null_str') origin,
								IFNULL(
										(SELECT CONCAT(w.state,' ',w.city)
										FROM warehouse w, load_warehouse lw
										WHERE w.warehouse_id = lw.warehouse_id
										AND lw.load_id = l.load_id
										AND lw.type = 'DEST'
										ORDER BY lw.activity_date DESC
										limit 1) 
								, '$this->null_str') dest
							FROM `load` l
							WHERE l.customer_id = $this->customer_id";
		$re = DB::query($sql);
		$t = new Template();
		$t->assign('loads', DB::to_array($re));
		}
		return $t->fetch(App::getTempDir().'cust_load_list.tpl');
	}
	function get_warehouses()
	{
		echo $_REQUEST['customer_id'];
		
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
		$sql = "SELECT note_id, notes, last_updated
								FROM customer_notes cn WHERE cn.customer_id = $this->customer_id
								ORDER BY last_updated";
		$re = DB::query($sql);
		
		
		$t = new Template();
		$t->assign('notes', DB::to_array($re));

		return $t->fetch(App::getTempDir().'cust_notes.tpl');
	}
	
	function get_search()
	{
		
		
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
	
	function show_cust_print($cust)
	{
		
		$t = new Template();
		
		isset($_GET['acct_owner']) ? $t->assign('acct_owner_name', App::get_username($_GET['acct_owner'])) : '';
		$t->assign('cust', DB::to_array($cust));
		
		$c .= $t->fetch(App::getTempDir().'cust_print.tpl');
		return $c;
	}
	
	
	function get_search_results()
	{
		$sql = "SELECT CONCAT('T', customer_id) p_customer_id, customer_id, name, address, city
				, phone, fax, state, contact_name FROM customer ";
		$clause = 'WHERE';
		$where='';
		if(isset($_REQUEST['customer_id']) && intval(trim($_REQUEST['customer_id'], 't T')) > 0)
		{
			$where .= " $clause customer_id = ".intval(trim($_REQUEST['customer_id'], 't T'));
			$clause = 'AND';
		}else{
			if(isset($_REQUEST['name']) && $_REQUEST['name'] != '')
			{
				$where .= " $clause name like '%".addslashes($_REQUEST['name'])."%'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['address']) && $_REQUEST['address'] !='')
			{
				$where .= " $clause address like '%".addslashes($_REQUEST['address'])."%'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['city']) && $_REQUEST['city'] !='')
			{
				$where .= " $clause city like '%".addslashes($_REQUEST['city'])."%'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['state']) && $_REQUEST['state'] !='')
			{
				$where .= " $clause state like '%".addslashes($_REQUEST['state'])."%'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['acct_owner']) && $_REQUEST['acct_owner'] >0)
			{
				$where .= " $clause acct_owner = $_REQUEST[acct_owner]";
				$clause = 'AND';
			}
		}
		if(!logged_in_as('admin') && !logged_in_as('super admin')){
			$where .= " $clause acct_owner = ".get_user_id();
			$clause = 'AND';
		}
		$sql .= $where;
		$re = DB::query($sql);
		//echo $sql;
		return $re;
	}
	
	function show_search_results($cust)
	{
		
		$t = new Template();
		//echo $sql;
		//$t->register_modifier("array2query", "array2query");
		//$t->register_modifier("in_array", "in_array");
		$t->assign('filters', $_GET);
		isset($_GET['start']) ? $start = $_GET['start'] : $start = 1;
		$p = new Paginator($cust, $start);
		//$acct_owners = Array('');
		$acct_owners = $this->get_acct_owners();
		isset($_GET['acct_owner']) ? $t->assign('sel_acct_owner', $_GET['acct_owner']) : '';
		$t->assign('acct_owners', $acct_owners);
		$t->assign('pag', $p->get());
		$t->assign('cust', $p->to_array($cust));
		$t->assign('admin', logged_in_as('admin'));
		$c='';
		$c .= $t->fetch(App::getTempDir().'cust_list.tpl');
		return $c;
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
		
		$r = $this->current_row();
		$t = new Template();
		$t->assign('cust', $r);
		$t->assign('cust_to_wh', $this->cust_to_wh);
		$t->assign('prefix', $this->prefix);
		$t->assign('customer_notes_portal', $this->get_notes());
		$t->assign('account_statuses', $this->account_status_list);
		$t->assign('acct_owners', $this->get_acct_owners());
		$t->assign('admin', logged_in_as('admin'));
		return $t->fetch(App::getTempDir().'customer_edit.tpl');
	}
	
	function get_acct_owners()
	{
		$sql = "SELECT user_id, username
				FROM users";
		$re = DB::query($sql);
		$ary = Array('');
		while($r = DB::fetch_assoc($re))
		{
			$ary[$r['user_id']] = $r['username'];
		}
		return $ary;
	}
	
	
	function get_customer_menu()
	{
		$c = "<table><tr>";
		$c .= "<td><a href='?page=customer'><div class='menu'>All Accounts</div></a></td>";
		$c .= "<td><a href='?page=customer&action=$this->search_edit'><div class='menu'>Search</div></a></td>";
		$c .= "<td><a href='?page=customer&action=$this->new_str'><div class='menu'>New</div></a></td>";
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
		$o->set_id("action=update&table=customer&".$pk_name."=".$_REQUEST[$pk_name]."&".$name."=");
		//$o->add_attribute('onchange', 'db_save(this.id, this.value);');
		$o->add_attribute('onchange', 'db_save(this);');
		$script = "
		<script type=\"text/javascript\">
			var i = document.getElementById('$name');
			i.setReturnFunction(onchange);
		</script>";
		
		return $o->render();
	}
	function portal_script()
	{
		return "
			<script type=\"text/javascript\">
			function get_portal(table)
			{
				var d = document.getElementById(table);
				d.innerHTML = 'Loading '+table;
				var portal = getFromURL('?page=$this->page&portal='+table+'&customer_id=$this->customer_id&action=portal&".SMALL_VIEW."');
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
$l = new customer_table();
	echo $l->render();
?>