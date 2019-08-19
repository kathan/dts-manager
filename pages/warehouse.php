<?php
//ini_set('display_errors', 'On');//Debug only
require_once"includes/global.php";
require_once"includes/dts_table.php";
require_once"includes/hidden_input.php";
require_once"includes/portal.php";
require_once("includes/Template.php");
require_once"includes/DB.php";
require_once"includes/Paginator.php";
/*
	
*/

class warehouse_table extends dts_table
{
	var $warehouse_id;
	function warehouse_table()
	{
		$this->dts_table("warehouse");
		if(isset($_REQUEST['warehouse_id']))
		{
			$this->warehouse_id = $_REQUEST['warehouse_id'];
			$this->current_row();
		}
		$this->prefix='D';
		$this->hide_delete();
		$this->hide_column('warehouse_id');
		
		//$this->set_label('customer_id', 'Customer');
		
		$this->add_table_params('page', 'warehouse');
	
		
		$i = new hidden_input('page', 'warehouse');
		$this->add_other_inputs($i);
		//print_r($this->times);
		
		$c =& $this->get_column('sun_open_time');
		$c->set_value_list($this->times);
		
		$c =& $this->get_column('sun_close_time');
		$c->set_value_list($this->times);
		
		$c =& $this->get_column('mon_open_time');
		$c->set_value_list($this->times);
		
		$c =& $this->get_column('mon_close_time');
		$c->set_value_list($this->times);
		
		$c =& $this->get_column('tues_open_time');
		$c->set_value_list($this->times);
		
		$c =& $this->get_column('tues_close_time');
		$c->set_value_list($this->times);
		
		$c =& $this->get_column('wed_open_time');
		$c->set_value_list($this->times);
		
		$c =& $this->get_column('wed_close_time');
		$c->set_value_list($this->times);
		
		$c =& $this->get_column('thurs_open_time');
		$c->set_value_list($this->times);
		
		$c =& $this->get_column('thurs_close_time');
		$c->set_value_list($this->times);
		
		$c =& $this->get_column('fri_open_time');
		$c->set_value_list($this->times);
		
		$c =& $this->get_column('fri_close_time');
		$c->set_value_list($this->times);
		
		$c =& $this->get_column('sat_open_time');
		$c->set_value_list($this->times);
		
		$c =& $this->get_column('sat_close_time');
		$c->set_value_list($this->times);
		
		
	}
	
	function current_row()
	{
		if(!isset($this->current_row))
		{
			$this->current_row = $this->get_row($this->warehouse_id);
		}
		return $this->current_row;
	}
	
	function render()
	{
		$code = '';
		//$code = "<title>".SITE_NAME."-Warehouse</title>";
		$code .= $this->db_script();
		$code .= $this->portal_script();
		$code .= $this->sortable_script();
		if(logged_in())
		{
			//$code .= "<div class='tab_sep'></div>";
			$code .= "<div class='content load_content'>";
			$GLOBALS['page_title'] = 'Warehouses';
			switch(get_action())
			{
				case $this->add_str:
					if($this->add())
					{
						$code .= $this->get_warehouses();
					}else{
						//$code .= "blah";
						$code .= $this->error();
						$code .= $this->feedback;
						$code .= $this->_render_edit();
					}
					break;
				case $this->cust_to_wh:
					$this->add_as_warehouse();
					echo "Warehouse added";
					header("location: ?page=warehouse&action=$this->edit_str&warehouse_id=$this->warehouse_id&$this->new_str");
					break;
				case $this->delete_str:
					$this->delete();
					//$code .= '<center><h2>Warehouse List</h2>';
					$code .= $this->show_search_results($this->get_search_results());
					//$code .= $this->get_warehouses();
					break;
				case $this->search_edit:
					$code .= $this->get_search();
					break;
				case $this->search:
					$code .= $this->show_search_results($this->get_search_results());
					break;
				case $this->edit_str:
					$code .= $this->edit_warehouse();
					break;
				case $this->add_str:
					$code .= "<script>
					function refresh_close()
					{
						window.opener.get_portal('warehouse');
						window.close();
					}
							window.onload = refresh_close;
						</script>";
					$this->add();
					break;
				case $this->new_str:
					//$code .=  '<center><h2>New Warehouse</h2>';
					//$code .= $this->new_warehouse();
					$this->create_new();
					header("location: http://".HTTP_ROOT."/?page=warehouse&action=$this->edit_str&warehouse_id=$this->warehouse_id&$this->new_str");
					break;
				case $this->print:
					
						if(logged_in_as('admin'))
						{
							$code .= $this->show_warehouse_print($this->get_search_results());
						}else{
							$code .= "No access.";
						}
						break;
				default:
					//$code .= '<center><h2>Warehouse List</h2>';
					//$code .= $this->get_warehouses();
					$code .= $this->show_search_results($this->get_search_results());
					break;
			}		
			$code .= "</div>";
		}
		
		return $code;
	}
	
	function show_warehouse_print($warehouse)
	{
		require_once('Template.php');
		require_once('Paginator.php');
		$t = new Template();
		
		//isset($_GET['acct_owner']) ? $t->assign('acct_owner_name', App::get_username($_GET['acct_owner'])) : '';
		//print_r(DB::to_array($warehouse));
		$t->assign('warehouse', DB::to_array($warehouse));
		
		$c .= $t->fetch(App::getTempDir().'warehouse_print.tpl');
		return $c;
	}
	
	function show_search_results($warehouse)
	{
		//require_once('Template.php');
		//require_once('Paginator.php');
		$t = new Template();
		//echo $sql;
		//$t->register_modifier("array2query", "array2query");
		//$t->register_modifier("in_array", "in_array");
		$t->assign('filters', $_GET);
		isset($_GET['start']) ? $start = $_GET['start'] : $start = 1;
		$p = new Paginator($warehouse, $start);
		//$acct_owners = Array('');
		//$acct_owners = array_merge($acct_owners, $this->get_acct_owners());
		//isset($_GET['acct_owner']) ? $t->assign('sel_acct_owner', $_GET['acct_owner']) : '';
		//$t->assign('acct_owners', $acct_owners);
		$t->assign('pag', $p->get());
		$t->assign('warehouse', $p->to_array($warehouse));
		$t->assign('admin', logged_in_as('admin'));
		$c='';
		$c .= $t->fetch(App::getTempDir().'warehouse_list.tpl');
		return $c;
	}
	
	function get_search_results()
	{
		
		//require_once("Paginator.php");
		$c ='';
		
		//$c .= '<center><h2>Warehouse Search Results</h2></center>';
		
		$sql = "SELECT CONCAT('D',warehouse_id) id, warehouse_id, address, name, city, state, phone, fax, contact_name FROM warehouse ";
		$clause = 'WHERE';
		$where='';
		
		if(isset($_REQUEST['warehouse_id']) && intval(trim($_REQUEST['warehouse_id'], 'd D')) > 0)
		{
			$where .= " $clause warehouse_id = ".intval(trim($_REQUEST['warehouse_id'], 'd D'));
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
		}
		$sql .= $where;
		//echo $sql;
		$re = DB::query($sql);
		//echo DB::error();
		return $re;
		
	}
	
	
	function create_new()
	{
		$this->add();
		$this->warehouse_id = $this->last_id;
		echo $this->error_str;
	}
	function get_search()
	{
		$c = '<!-- warehouse search start --><center><h2>Warehouse Search</h2>';
		$c .= "Use % as a wildcard character";
		require_once("includes/submit_input.php");
		
		$this->set_submit_input($si);	
		
		$si = new submit_input($this->search, $this->action);
		$f =& $this->get_form();
		$f->set_get();
		$this->omit_all_columns();
		
		$this->unhide_column('warehouse_id');
		$this->insert_column('warehouse_id');
		$this->insert_column('name');
		$this->insert_column('city');
		$this->insert_column('state');
		$this->set_submit_input($si);
		$c .= $this->_render_edit();
		$c .= '<!-- warehouse search end -->';
		return $c;
	}
	
	function get_warehouses()
	{
		require_once"includes/portal.php";
		$p = new portal("	SELECT warehouse_id, CONCAT('$this->prefix', warehouse_id) id, name, address, city, state	
							FROM warehouse w
							");
		$p->set_table('warehouse');
		$p->set_row_action("\"row_clicked('\$id', '\$pk', '\warehouse')\";");
		$p->hide_column('warehouse_id');
		$p->set_primary_key('warehouse_id');
		return $p->render();
	}
	function get_warehouse_menu()
	{
		$c = "<table><tr>";
		$c .= "<td><a href='http://".HTTP_ROOT."/?page=warehouse'><div class='menu'>All Warehouses</div></a></td>";
		$c .= "<td><a href='http://".HTTP_ROOT."/?page=warehouse&action=$this->search_edit'><div class='menu'>Search</div></a></td>";
		$c .= "<td><a href='http://".HTTP_ROOT."/?page=warehouse&action=$this->new_str'><div class='menu'>New</div></a></td>";
		$c .= $this->back_button();
		$c .= "</tr></table>";
		return $c;
	}
	
	function new_warehouse()
	{
		$c ='<script>
				function submit_close()
				{
					var f = document.getElementById("new_form");
					f.submit();
				}
				function cancel_close()
				{
					window.close();
				}
				</script>';
		$c .= '<table border=0><tr>';
		$c .= "<form id='new_form' onsubmit=submit_close'' method='post'>";
		$c .= "<input type='hidden' name='page' value='warehouse'>";
		$c .= "<input type='hidden' name='action' value='Add'>";
	
		$c .= '<tr><td>Customer</td><td>'.$this->fetch_edit('customer_id', safe_get($_REQUEST['customer_id'])).'</td></tr>';
		$c .= '<tr><td>Name</td><td>'.$this->fetch_edit('name').'</td></tr>';
		$c .= '<tr><td>Address</td><td>'.$this->fetch_edit('address').'</td></tr>';
		$c .= '<tr><td>City</td><td>'.$this->fetch_edit('city').'</td></tr>';
		$c .= '<tr><td>State</td><td>'.$this->fetch_edit('state').'</td></tr>';
		$c .= '<tr><td>Zip</td><td>'.$this->fetch_edit('zip').'</td></tr>';
		$c .= '<tr><td>Phone</td><td>'.$this->fetch_edit('phone').'</td></tr>';
		$c .= '<tr><td>Fax</td><td>'.$this->fetch_edit('fax').'</td></tr>';
		$c .= '<tr><td>Notes</td><td>'.$this->fetch_edit('notes').'</td></tr>';
		$c .= '<tr><td>Directions</td><td>'.$this->fetch_edit('directions').'</td></tr>';
		$c .= '<tr><td colspan=2>';
		$c .= '<table>
					<tr>
						<th></th>
						<th>
							Open Time
						</th>
						<th>
							Close Time
						</th>
					</tr>';
		$c .= '
					<tr>
						<td>Sun</td>
						<td>'.$this->fetch_edit('sun_open_time').'</td>
						<td>'.$this->fetch_edit('sun_close_time').'</td>
					</tr>';
		$c .= '
					<tr>
						<td>Mon</td>
						<td>'.$this->fetch_edit('mon_open_time').'</td>
						<td>'.$this->fetch_edit('mon_close_time').'</td>
					</tr>';
		$c .= '
					<tr>
						<td>Tues</td>
						<td>'.$this->fetch_edit('tues_open_time').'</td>
						<td>'.$this->fetch_edit('tues_close_time').'</td>
					</tr>';
		$c .= '
					<tr>
						<td>Wed</td>
						<td>'.$this->fetch_edit('wed_open_time').'</td>
						<td>'.$this->fetch_edit('wed_close_time').'</td>
					</tr>';
		$c .= '
					<tr>
						<td>Thurs</td>
						<td>'.$this->fetch_edit('thurs_open_time').'</td>
						<td>'.$this->fetch_edit('thurs_close_time').'</td>
					</tr>';
		$c .= '
					<tr>
						<td>Fri</td>
						<td>'.$this->fetch_edit('fri_open_time').'</td>
						<td>'.$this->fetch_edit('fri_close_time').'</td>
					</tr>';
		$c .= '
					<tr>
						<td>Sat</td>
						<td>'.$this->fetch_edit('sat_open_time').'</td>
						<td>'.$this->fetch_edit('sat_close_time').'</td>
					</tr>';
		$c .= "</table></td></tr>";
		$c .= '<tr><td><input type="button" onclick="submit_close()" value="Save"></td></tr>';
		$c .= '<tr><td><input type="button" onclick="cancel_close()" value="Cancel"></td></tr>';
		$c .= "</form>";
		$c .= '</tr></table>';
	
		return $c;
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
			$o->set_id("action=$this->update&table=$this->name&".$pk_name."=".$_REQUEST[$pk_name]."&".$name."=");
			$o->add_attribute('onchange', 'db_save(this);column_updated(this);');
			//$o->add_attribute('onchange', 'db_save(this.id, this.value);column_updated(this);');
		$script = "<script>
		var i = document.getElementById('$name');
		i.setReturnFunction(onchange);
		</script>";
		}
		
		
		return $o->render();
	}
	

	function edit_warehouse()
	{
		$sql = "SELECT	w.*,
					DATE_FORMAT(sun_open_time, '$this->time_format') sun_open_time,
					DATE_FORMAT(sun_close_time, '$this->time_format') sun_close_time,
					DATE_FORMAT(mon_open_time, '$this->time_format') mon_open_time,
					DATE_FORMAT(mon_close_time, '$this->time_format') mon_close_time,
					DATE_FORMAT(tues_open_time, '$this->time_format') tues_open_time,
					DATE_FORMAT(tues_close_time, '$this->time_format') tues_close_time,
					DATE_FORMAT(wed_open_time, '$this->time_format') wed_open_time,
					DATE_FORMAT(wed_close_time, '$this->time_format') wed_close_time,
					DATE_FORMAT(thurs_open_time, '$this->time_format') thurs_open_time,
					DATE_FORMAT(thurs_close_time, '$this->time_format') thurs_close_time,
					DATE_FORMAT(fri_open_time, '$this->time_format') fri_open_time,
					DATE_FORMAT(fri_close_time, '$this->time_format') fri_close_time,
					DATE_FORMAT(sat_open_time, '$this->time_format') sat_open_time,
					DATE_FORMAT(sat_close_time, '$this->time_format') sat_close_time
				FROM warehouse w
				WHERE warehouse_id = $this->warehouse_id";
		$r = DB::query($sql);
		//echo $sql;
		$w = DB::fetch_assoc($r);
		$t = new Template();
		$t->assign('w', $w);
		$t->assign('wh_to_cust', $this->wh_to_cust);
		//echo $w['sun_open_time'];
		$t->assign('times', $this->times);
		//$t->assign('action', $this->times);
		if(isset($_REQUEST[$this->new_str]))
		{
			
			$t->assign('new', true);
			$t->assign('delete_str', $this->delete_str);
		}
		
		$t->assign('action', $this->update);
		
		return $t->fetch(App::getTempDir().'warehouse_edit.tpl');
	}
	
	
	function add_as_warehouse()
	{
		$sql = "INSERT INTO warehouse(name, address, city, state, zip, phone, fax, contact_name)
				SELECT name, address, city, state, zip, phone, fax, contact_name
				FROM customer
				WHERE customer_id = $_REQUEST[customer_id]";
		$r = db_query($sql);
		if(db_error())
		{
			echo db_error();
		}
		$this->warehouse_id = db_insertid();
	}
}

$w = new warehouse_table();
//$w->add_virtual_column("DATE_FORMAT(sun_open_time, '%l:%i %r')", 'sun_open_time');
echo $w->render();
//echo $w->sql;
?>