<?php
require_once "includes/app.php";
require_once "includes/global.php";
require_once "includes/auth.php";
require_once "includes/dts_table.php";
require_once('DB.php');

class carrier_table extends dts_table
{
	var $carrier_id;
	function carrier_table()
	{
		$this->dts_table('carrier');
		$this->prefix='S';
		$this->hide_delete();
		$this->hide_column('carrier_id');
		$this->add_table_params('page', 'carrier');
			
		require_once"includes/hidden_input.php";
		$i = new hidden_input('page', 'carrier');
		$this->add_other_inputs($i);
		
		
		$c =& $this->get_column('carrier_rep');
		$c->set_value_list($this->get_users());
		//$c->set_parent_label_column('username');
		
		//$this->tab_menu->add_tab("http://".HTTP_ROOT."/?page=carrier", 'List');
		//$this->tab_menu->add_tab("http://".HTTP_ROOT."/?page=carrier", 'Search');
	}
	
	
	function render()
	{
		$code = '';
		
		if(isset($_REQUEST[$this->portal]))
		{
			switch(safe_get($_REQUEST[$this->portal]))
			{
				case $this->load:
					$code .= $this->get_loads();
					break;
			}			
		}else{
			$GLOBALS['page_title'] = "Carriers";
			$code='';
			//$code = "<title>".SITE_NAME."-Carriers</title>";
		
		if(logged_in())
		{
			//$code .= "<title>".SITE_NAME."-Carriers</title>";
			$code .= "<script src='sortable.js'></script>
				<script src='db_save.js.php'></script>
				";
				$code .= $this->portal_script();
				//$code .= '<!-- carrier menu start -->';
				//$code .= $this->tab_menu->render();
				//$code .= "<div class='tab_sep'></div>";
				//$code .= '<!-- carrier menu end -->';
				//$code .= $this->get_carrier_menu();
				$code .= "<div class='content load_content'>";
				//include_once("includes/portal_style.php");
				switch(get_action())
				{
					case $this->add_str:
						
						if($this->add())
						{
							
							//$code .= $this->get_carrier_list();
							$code .= $this->get_search_results();
						}else{
							//$code .= "blah";
							$code .= $this->error();
							$code .= $this->feedback;
							$code .= $this->_render_edit();
							
						}
						break;
					case $this->delete_str:
						$this->delete();
						//$code .= '<center><h2>Carrier List</h2>';
						//$code .= $this->get_carrier_list();
						$code .= $this->get_search_results();
						break;
					case $this->search:
						
						$code .= $this->show_search_results($this->get_search_results());	
						break;
					case $this->search_edit:
						$code .= $this->get_search_edit();
						break;
					case $this->edit_str:
					
						$code .= $this->get_carrier_edit();
						break;
					case $this->new_str:
						//$code .= '<center><h2>New Carrier</h2>';
						//$code .= $this->_render_edit();
						$this->create_new();
						header("location: http://".HTTP_ROOT."/?page=carrier&action=$this->edit_str&carrier_id=$this->carrier_id&$this->new_str");
						break;
					case $this->print:
						if(logged_in_as('admin'))
						{
							$code .= $this->show_carrier_print($this->get_search_results());
						}else{
							$code .= "No access.";
						}
						break;
					default:
						//$code .= '<center><h2>Carrier List</h2>';
						//$code .= $this->get_carrier_list();
						//$code .= "boo";
						$code .= $this->show_search_results($this->get_search_results());
						break;
				}
				$code .= "</div>";
			}
		}
		return $code;
	}
	function create_new()
	{
		
		$this->add();
		$this->carrier_id = $this->last_id;
	}
	function show_carrier_print($carrier)
	{
		require_once('Template.php');
		require_once('Paginator.php');
		$t = new Template();
		
		//isset($_GET['acct_owner']) ? $t->assign('acct_owner_name', App::get_username($_GET['acct_owner'])) : '';
		$t->assign('carrier', DB::to_array($carrier));
		
		$c .= $t->fetch(App::$temp.'carrier_print.tpl');
		return $c;
	}
	function show_search_results($carrier)
	{
		require_once('Template.php');
		require_once('Paginator.php');
		$t = new Template();
		//echo $sql;
		$t->register_modifier("array2query", "array2query");
		$t->register_modifier("in_array", "in_array");
		$t->assign('filters', $_GET);
		isset($_GET['start']) ? $start = $_GET['start'] : $start = 1;
		$p = new Paginator($carrier, $start);
		$acct_owners = Array('');
		//$acct_owners = array_merge($acct_owners, $this->get_acct_owners());
		isset($_GET['acct_owner']) ? $t->assign('sel_acct_owner', $_GET['acct_owner']) : '';
		$t->assign('acct_owners', $acct_owners);
		$t->assign('pag', $p->get());
		$t->assign('carrier', $p->to_array($carrier));
		$t->assign('admin', logged_in_as('admin'));
		$c .= $t->fetch(App::$temp.'carrier_list.tpl');
		return $c;
	}
	
	function get_search_results()
	{
		$sql = "SELECT CONCAT('S', carrier_id) id, c.* FROM carrier c ";
		$clause = 'WHERE';
		$where='';
		if(isset($_REQUEST['carrier_id']) && intval(trim($_REQUEST['carrier_id'], 's S')) > 0)
		{
			$where .= " $clause carrier_id = ".intval(trim($_REQUEST['carrier_id'], 's S'));
			$clause = 'AND';
		}else{
			if(isset($_REQUEST['name']) && $_REQUEST['name'] != '')
			{
				$where .= " $clause name like '%".addslashes($_REQUEST['name'])."%'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['phys_address']) && $_REQUEST['phys_address'] !='')
			{
				$where .= " $clause phys_address like '%".addslashes($_REQUEST['phys_address'])."%'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['phys_city']) && $_REQUEST['phys_city'] !='')
			{
				$where .= " $clause phys_city like '%".addslashes($_REQUEST['phys_city'])."%'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['phys_state']) && $_REQUEST['phys_state'] !='')
			{
				$where .= " $clause phys_state like '%".addslashes($_REQUEST['phys_state'])."%'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['mc_number']) && $_REQUEST['mc_number'] !='')
			{
				$where .= " $clause mc_number like '%".addslashes($_REQUEST['mc_number'])."%'";
				$clause = 'AND';
			}
		}
		$sql .= $where;
		//echo $sql;
		$re = DB::query($sql);
		
		return $re;
	}
	function get_search_edit()
	{
		require_once("includes/submit_input.php");
		$si = new submit_input($this->search, 'action');
		$f =& $this->get_form();
		$f->set_get();
		$this->omit_all_columns();
		
		$this->unhide_column('carrier_id');
		$this->insert_column('carrier_id');
		$this->insert_column('name');
		$this->insert_column('phys_city');
		$this->insert_column('phys_state');
		$this->insert_column('mc_number');
		
		$this->set_submit_input($si);
		$c = "<!-- start carrier search --><center><h2>Carrier Search</h2>";
		$c .= "Use % as a wildcard character";
		$c .= $this->_render_edit();
		$c .= "</fieldset><!-- end carrier search -->";
		return $c;
	}
	
	function get_loads()
	{
		require_once"includes/portal.php";
		
		$p = new portal("SELECT 	l.load_id,
								IFNULL(
									(SELECT CONCAT(IF(cancelled,'<span style=\"$this->cancel_style\">',IF(rating='Expedited','<span style=\"$this->expedited_style\">','')),w.state,' ',w.city)
									FROM warehouse w, load_warehouse lw
									WHERE w.warehouse_id = lw.warehouse_id
									AND lw.load_id = l.load_id
									AND lw.type = 'PICK'
									ORDER BY lw.activity_date ASC
									LIMIT 1) 
								, '$this->null_str') origin,
								IFNULL(
									(SELECT CONCAT(IF(cancelled,'<span style=\"$this->cancel_style\">',IF(rating='Expedited','<span style=\"$this->expedited_style\">','')),w.state,' ',w.city)
									FROM warehouse w, load_warehouse lw
									WHERE w.warehouse_id = lw.warehouse_id
									AND lw.load_id = l.load_id
									AND lw.type = 'DEST'
									ORDER BY lw.activity_date DESC
									limit 1) 
								, '$this->null_str') dest
							FROM `load` l, load_carrier lc
							WHERE lc.carrier_id = $_REQUEST[carrier_id]
							AND l.load_id = lc.load_id");
		$p->set_table($this->load);
		$p->hide_column('load_id');
		$p->set_primary_key('load_id');
		$p->set_row_action("\"row_clicked('\$id', '\$pk', '\$table')\";");
		return $p->render();
	}
	function get_carrier_menu()
	{
		$t = new Template();
		return $t->fetch(App::$temp.'carrier_menu.tpl');
	}
	
	function get_carrier_list()
	{
		require_once"includes/portal.php";
		
		$p = new portal("	SELECT carrier_id, CONCAT('$this->prefix', carrier_id) id, name, phys_city city, phys_state state
							FROM `carrier`");
		$p->set_table($this->carrier);
		$p->set_row_action("\"row_clicked('\$id', '\$pk', '\carrier')\";");
		$p->set_primary_key('carrier_id');
		$p->hide_column('carrier_id');
		return $p->render();
	}
	
	function get_carrier_edit()
	{
		
		require_once('Template.php');
		$r = $this->get_row($_GET['carrier_id']);
		
		$temp = new Template();
		$temp->assign('carrier', $r);
		$temp->assign('users', $this->get_users());
		$temp->assign('admin', logged_in_as('admin'));
		$temp->assign('prefix', $this->prefix);
		//include(App::$temp.'carrier_edit.tpl');
		return $temp->fetch(App::$temp.'carrier_edit.tpl');
	}
	
	
	
	function fetch_edit($name, $value, $protected=false)
	{
		$c =& $this->get_column($name);
		$pk_obj =& $this->get_primary_key();
		$pk_name = $pk_obj->get_name();
		if($protected)
		{
			return "<div class='faux_edit'>".$c->get_view_html($value)."</div>";
			
		}else{
			$o = $c->get_edit_html($value);
			$o->set_id("action=update&table=$_REQUEST[page]&".$pk_name."=".$_REQUEST[$pk_name]."&".$name."=");
			$o->add_attribute('onchange', 'db_save(this);');
			$script = "<script>
		var i = document.getElementById('$name');
		i.setReturnFunction(onchange);
		</script>";
			return $o->render();
		}
	}
	
	function portal_script()
	{
		return "
			<script>
			function get_portal(table)
			{
				var d = document.getElementById(table);
				d.innerHTML = 'Loading '+table;
				var portal = getFromURL('http://".HTTP_ROOT."/?page=$this->name&portal='+table+'&carrier_id=".safe_get($_REQUEST['carrier_id'])."&action=portal&".SMALL_VIEW."');
				d.innerHTML = '';
				d.innerHTML = portal;
			}
			</script>";
			
	}
}

$l = new carrier_table();
echo $l->render();
?>