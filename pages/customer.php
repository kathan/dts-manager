<?php
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

class customer_table extends dts_table{
	var $customer_id;
	function __construct(){
		
		parent::__construct("customer");
		$this->page = $_GET['page'];
		if(isset($_REQUEST['customer_id'])){
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
	
	function render(){
		$code ='';
		if(Auth::loggedIn()){
			
			if(isset($_REQUEST[$this->portal])){
				switch(safe_get($_REQUEST[$this->portal])){
					
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
				
				switch(get_action()){
					case $this->wh_to_cust:
						$this->add_as_customer();
						header("location: ?page=$this->page&action=$this->edit_str&customer_id=$this->customer_id&$this->new_str");
						break;
					case $this->add_str:
						if($this->add()){			
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
						if($this->add()){
							$this->customer_id = $this->last_id;
							$code .= $this->get_edit();
						}else{
							$code .= $this->error_str;
						}
						break;
					case $this->print:
						if(Auth::loggedInAs('admin')){
							$code .= $this->show_cust_print($this->get_search_results());
						}else{
							$code .= "No access.";
						}
						break;
					default:
						
						$code .= $this->show_search_results($this->get_search_results());
						break;
				}
				if(!isset($_GET['sml_view'])){
					$code .= $this->portal_script();
					$code .= "<head><script src='js/sortable.js'></script>
							<script src='js/db_save.js'></script>
							<script type=\"text/javascript\">
			
							function popUp(URL){
								day = new Date();
								id = day.getTime();
								eval(\"page\" + id + \" = window.open(URL, '\" + id + \"', 		'toolbar=0,location=0,statusbar=0,menubar=0,resizable=1,width=600,height=600,left = 640,top = 225');\");
							}
							</script>";
			
					$code .= "<div class='content load_content'>";
				}
				$code .= "</div>";
			}
		}
		$feedback='';
		if($this->error()){
			$feedback = $this->error();
		}
		return $feedback.$code;
	}
	
	function check_for_existing($cust){
            $binds = [$cust['address'], $cust['name'], $cust['name'], $cust['address'], $cust['address'], "$cust[zip]%", $cust['zip']];
		$sql = "SELECT c.*, ? REGEXP '^[0-9]+', address REGEXP '^[0-9]+', soundex(address) 
				FROM customer c
				WHERE (soundex(name) like CONCAT(soundex(?), '%')
				OR soundex(?) like CONCAT(soundex(name), '%'))
				AND (soundex(address) like CONCAT(soundex(?), '%')
				OR soundex(?) like CONCAT(soundex(address), '%'))
				AND (zip like ?
				OR ? like CONCAT(zip, '%'))";
		$stmt = App::$db->prepare($sql);
		$result = $stmt->execute($binds);
		return $stmt;
	}
	
	function create_new(){
		
		set_post('acct_owner', Auth::getUserId());
		$this->add();
		$this->customer_id = $this->last_id;
	}

	function get_customers(){
		$binds = [Auth::getUserId()];
		$sql = "	SELECT	customer_id, CONCAT('$this->prefix', customer_id) id, name customer_name, city, state, (SELECT username FROM `users` u WHERE u.user_id = c.acct_owner) acct_owner
							FROM customer c
							";
		if(!Auth::loggedInAs('admin') && !Auth::loggedInAs('super admin')){
			$sql .= " WHERE acct_owner = ?";
		}
		$p = new portal($sql, $binds);
		$p->set_row_action("\"row_clicked('\$id', '\$pk', '\$table')\";");
		$p->set_primary_key('customer_id');
		$p->hide_column('customer_id');
		$p->set_table('customer');
		return $p->render();
	}
	
	function get_loads(){
		//Added 10/15/13
		$t = new Template();
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
							WHERE l.customer_id = ?";
                
                $binds = [$this->customer_id];
			$stmt = App::$db->prepare($sql);
			$result = $stmt->execute($binds);
			$t->assign('loads', $stmt->fetchAll(PDO::FETCH_ASSOC));
		}
		
		
		return $t->fetch(App::getTempDir().'cust_load_list.tpl');
	}

	function get_warehouses(){
		echo $_REQUEST['customer_id'];
		
		$p = new portal("	SELECT warehouse_id, name, city, state
							FROM warehouse w
							WHERE w.customer_id = ?", [$this->customer_id]);
		$p->set_table('warehouse');
		$p->hide_column('warehouse_id');
		$p->set_primary_key('warehouse_id');
		return $p->render();
	}
	
	function get_notes(){
		$sql = "SELECT note_id, notes, last_updated
								FROM customer_notes cn WHERE cn.customer_id = ?
								ORDER BY last_updated";
		$stmt = App::$db->prepare($sql);
		$re = $stmt->execute([$this->customer_id]);
		
		$t = new Template();
		$t->assign('notes', $stmt->fetchAll(PDO::FETCH_ASSOC));

		return $t->fetch(App::getTempDir().'cust_notes.tpl');
	}
	
	function get_search(){
		$c = '<h2>Customer Search</h2>';
		$si = new submit_input($this->search, $this->action);
		$f =& $this->get_form();
		$f->set_get();
		$this->omit_all_columns();
		$this->unhide_column('customer_id');
		$this->add_virtual_column('customer_id', 'customer_id');
		$this->add_virtual_column('name', 'name');
		$this->add_virtual_column('city', 'city');
		$this->add_virtual_column('state', 'state');
		$users =  $this->get_users();
		$s = new select_input('acct_owner', 'user_id', 'username', $users);
		$f = $this->get_form();
		$f->add_input($s);
		$this->set_submit_input($si);
		$c .= $this->_render_edit();
		return $c;
	}
	
	function show_cust_print($cust){
		
		$t = new Template();
		
		isset($_GET['acct_owner']) ? $t->assign('acct_owner_name', App::get_username($_GET['acct_owner'])) : '';
		$t->assign('cust', DB::to_array($cust));
		
		$c .= $t->fetch(App::getTempDir().'cust_print.tpl');
		return $c;
	}
	
	function get_search_results(){
		$sql = "SELECT CONCAT('T', customer_id) p_customer_id, customer_id, name, address, city
				, phone, fax, state, contact_name FROM customer ";
		$clause = 'WHERE';
		$where='';
        // $binds = [];
		if(isset($_REQUEST['customer_id']) && intval(trim($_REQUEST['customer_id'], 't T')) > 0){
            $binds[] = intval(trim($_REQUEST['customer_id'], 't T'));
			$where .= " $clause customer_id = ?";
			$clause = 'AND';
		}else{
			if(isset($_REQUEST['name']) && $_REQUEST['name'] != ''){
				$binds[] = "%".addslashes($_REQUEST['name'])."%";
				$where .= " $clause name like ?";
				$clause = 'AND';
			}
			if(isset($_REQUEST['address']) && $_REQUEST['address'] !=''){
				$binds[] = "%".addslashes($_REQUEST['address'])."%";
				$where .= " $clause address like ?";
				$clause = 'AND';
			}
			if(isset($_REQUEST['city']) && $_REQUEST['city'] !=''){
				$binds[] = "%".addslashes($_REQUEST['city'])."%";
				$where .= " $clause city like ?";
				$clause = 'AND';
			}
			if(isset($_REQUEST['state']) && $_REQUEST['state'] !=''){
				$binds[] = "%".addslashes($_REQUEST['state'])."%";
				$where .= " $clause state like ?";
				$clause = 'AND';
			}
			if(isset($_REQUEST['acct_owner']) && $_REQUEST['acct_owner'] >0){
				$binds[] = $_REQUEST['acct_owner'];
				$where .= " $clause acct_owner = ?";
				$clause = 'AND';
			}
		}
		if(!Auth::loggedInAs('admin') && !Auth::loggedInAs('super admin')){
			$binds[] = Auth::getUserId();
			$where .= " $clause acct_owner = ?";
			$clause = 'AND';
		}
		$sql .= $where;
		$stmt = App::$db->prepare($sql);
		$result = $stmt->execute($binds);
		return $stmt;
	}
	
	function show_search_results($cust){
		
		$t = new Template();
		$t->assign('filters', $_GET);
		isset($_GET['start']) ? $start = $_GET['start'] : $start = 1;
		$p = new Paginator($cust, $start);
		$acct_owners = $this->get_acct_owners();
		isset($_GET['acct_owner']) ? $t->assign('sel_acct_owner', $_GET['acct_owner']) : '';
		$t->assign('acct_owners', $acct_owners);
		$t->assign('pag', $p->get());
		$t->assign('cust', $p->to_array($cust));
		$t->assign('admin', Auth::loggedInAs('admin'));
		$c='';
		$c .= $t->fetch(App::getTempDir().'cust_list.tpl');
		return $c;
	}
	
	function current_row(){
            if(!isset($this->current_row)){
		$this->current_row = $this->get_row($this->customer_id, true);
            }
            return $this->current_row;
	}
        
	function get_edit(){
		$r = $this->current_row();
		$t = new Template();
		$t->assign('cust', $r);
		$t->assign('cust_to_wh', $this->cust_to_wh);
		$t->assign('prefix', $this->prefix);
		$t->assign('customer_notes_portal', $this->get_notes());
		$t->assign('account_statuses', $this->account_status_list);
		$t->assign('acct_owners', $this->get_acct_owners());
		$t->assign('admin', Auth::loggedInAs('admin'));
		return $t->fetch(App::getTempDir().'customer_edit.tpl');
	}
	
	function get_acct_owners(){
		$sql = "SELECT user_id, username
				FROM `users`";
		$re = App::$db->query($sql);
		$ary = [''];
		while($r = $re->fetch(PDO::FETCH_ASSOC)){
            $ary[$r['user_id']] = $r['username'];
		}
		return $ary;
	}
	
	
	function get_customer_menu(){
		$c = "<table><tr>";
		$c .= "<td><a href='?page=customer'><div class='menu'>All Accounts</div></a></td>";
		$c .= "<td><a href='?page=customer&action=$this->search_edit'><div class='menu'>Search</div></a></td>";
		$c .= "<td><a href='?page=customer&action=$this->new_str'><div class='menu'>New</div></a></td>";
		$c .= $this->back_button();
		$c .= "</tr></table>";
		return $c;
	}
	
	function fetch_edit($name, $value){
		$c =& $this->get_column($name);
		$pk_obj =& $this->get_primary_key();
		$pk_name = $pk_obj->get_name();
		
		$o = $c->get_edit_html($value);
		$o->set_id("action=update&table=customer&".$pk_name."=".$_REQUEST[$pk_name]."&".$name."=");
		$o->add_attribute('onchange', 'db_save(this);');
		$script = "
		<script type=\"text/javascript\">
			var i = document.getElementById('$name');
			i.setReturnFunction(onchange);
		</script>";
		
		return $o->render();
	}
	function portal_script(){
		return "
			<script type=\"text/javascript\">
			function get_portal(table){
				var d = document.getElementById(table);
				d.innerHTML = 'Loading '+table;
				var portal = getFromURL('?page=$this->page&portal='+table+'&customer_id=$this->customer_id&action=portal&".SMALL_VIEW."');
				d.innerHTML = '';
				d.innerHTML = portal;
			}
			</script>";
			
	}
	
	function add_as_customer(){
		$sql = "INSERT INTO customer(name, address, city, state, zip, phone, fax, contact_name, acct_owner)
				SELECT name, address, city, state, zip, phone, fax, contact_name, ".Auth::getUserId()."
				FROM warehouse
				WHERE warehouse_id = $_REQUEST[warehouse_id]";
		$r = db_query($sql);
		if(db_error()){
			echo db_error();
		}
		$this->customer_id = db_insertid();
		$this->current_row();
	}
}
$l = new customer_table();
	echo $l->render();
?>