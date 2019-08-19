<?php
global $search_edit;
global $search;
$search = 'Search';
$search_edit = 'search_edit';
ini_set('display_errors', 'On');//Debug only
require_once"includes/global.php";
require_once"includes/auth.php";
//echo $_REQUEST['customer_id'];
if(logged_in())
{
		echo "<title>".SITE_NAME."-Customers</title>";
		echo "<head><script src='sortable.js'></script>
		<script src='db_save.js'></script>
		<SCRIPT LANGUAGE=\"JavaScript\">
		function get_portal(table)
		{
			var d = document.getElementById(table);
			d.innerHTML = 'Loading '+table;
			//alert(document.URL);
			var portal = getFromURL('http://".HTTP_ROOT."/?page='+ table +'&customer_id=".safe_get($_REQUEST['customer_id'])."&action=portal&popup');
			//alert(\"====\"+portal);
			d.innerHTML = '';
			d.innerHTML = portal;
		}
		function popUp(URL)
		{
			day = new Date();
			id = day.getTime();
			eval(\"page\" + id + \" = window.open(URL, '\" + id + \"', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=600,height=600,left = 640,top = 225');\");
		}
		</script>";
		
		include_once("includes/portal_style.php");
		echo "</head>";
		require_once"includes/table.php";
		global $t;
		$t = new table("customer");
		$t->set_auto_save();
		$t->hide_delete();
		$t->hide_column('customer_id');
		$t->add_table_params('page', 'customer');
	
		require_once"includes/hidden_input.php";
		$i = new hidden_input('page', 'customer');
		$t->add_other_inputs($i);

		$c =& $t->get_column('acct_owner');
		$c->set_parent_label_column('username');
	
		$c =& $t->get_column('lead_source');
		$c->set_parent_label_column('username');
		echo get_customer_menu();
		echo "<table width='100%' border=0><tr><td width='50%'>";
			
		
		switch(get_action())
		{
			case $search_edit:
				echo '<center><h2>Customer Search</h2>';
				echo "Use % as a wildcard character";
				echo get_search($t);
				break;
			case $search:
				echo '<center><h2>Customer Search Results</h2>';
				echo $t->render();
				break;
			case $t->edit_str:
				echo '<center><h2>Customer Edit</h2>';
				echo get_edit($t);
				break;
			case $t->new_str:
				echo '<center><h2>New Customer</h2>';
				echo $t->render();
				break;
			default:
				echo '<center><h2>Customer List</h2>';
				//echo $t->render();
				echo get_customers();
				break;
		}
		
		echo "</td></tr></table>";
}

function get_customers()
{
	require_once"includes/portal.php";
	$p = new portal("	SELECT	customer_id, name, address, city, state, zip, phone, fax,
								(SELECT username FROM users u WHERE u.user_id = c.acct_owner) account_owner
						FROM customer c
						");
	$p->set_primary_key('customer_id');
	$p->set_table('customer');
	return $p->render();
}

function get_loads()
{

	$t = new table("load");
	$t->hide_delete();
	$t->add_table_params('page', 'load');
	
	//$c =& $t->get_column('customer_id');
	//$c->set_parent_label_column('name');
	
	$c =& $t->get_column('carrier_id');
	$c->set_parent_label_column('name');
	
	$c =& $t->get_column('order_by');
	$c->set_parent_label_column('username');
	
	$t->omit_all_columns();
	
	$c =& $t->get_column('carrier_id');
	$c->set_parent_label_column('name');
	$t->add_virtual_column('load_id', 'load');
	$t->add_virtual_column("DATE_FORMAT((SELECT CASE
								WHEN ordered > booked
									THEN ordered
								WHEN booked > checked_in
									THEN booked
								WHEN checked_in > loaded 
									THEN checked_in
								WHEN loaded > delivered
									THEN loaded
								ELSE delivered
							END), '%c/%e/%Y')", 'Activity Date');
	$t->add_virtual_column("(SELECT CONCAT(city, ' ', state) FROM warehouse w WHERE w.warehouse_id = load.carrier_id)", 'Location');
	$t->add_virtual_column('(SELECT name FROM carrier c WHERE c.carrier_id = load.carrier_id)', 'Carrier');
	return $t->_render_list();
}

function get_warehouses()
{
	require_once"includes/table.php";
	$t = new table("warehouse");
	$t->hide_delete();
	$t->add_table_params('page', 'warehouse');
	$t->omit_all_columns();
	$t->add_virtual_column('name', 'name');
	$t->add_virtual_column('city', 'city');
	$t->add_virtual_column('state', 'state');
	return $t->_render_list();
}

function get_notes()
{
	require_once"includes/table.php";
	$t = new table("customer_notes");
	$t->hide_delete();
	$t->add_table_params('page', 'customer_notes');
	$t->omit_all_columns();
	$t->add_virtual_column('(SELECT c.name FROM customer c WHERE c.customer_id = customer_notes.customer_id)','customer');
	$t->add_virtual_column('last_updated', 'last_updated');
	
	return $t->_render_list();
}
function get_search(&$t)
{
	global $search;
	require_once("includes/submit_input.php");
	$si = new submit_input($search, $search);
	$f =& $t->get_form();
	$f->set_get();
	$t->set_submit_input($si);
	return $t->_render_edit();
}

function get_edit(&$t)
{
	
	$c = '';
	
	$r = $t->get_row($_REQUEST['customer_id']);
	$t->hide_delete();
	$t->hide_column('customer_id');
	$t->add_table_params('page', 'customer');
	
	require_once"includes/hidden_input.php";
	$i = new hidden_input('page', 'customer');
	$t->add_other_inputs($i);
	
	//====Main====
	$c .= "<table><tr><td>";
	//============
	$c .= "<fieldset><legend>Main</legend><table>";

	//====Customer Name====
	$c .= '<tr><td>Name:</td><td>'.fetch_edit('name', $r['name']).'</td></tr>';
	//=====================
	
	//====Customer Address====
	$c .= '<tr><td>Address:</td><td>'.fetch_edit('address', $r['address']).'</td></tr>';
	//=====================
	
	//====Customer City State Zip====
	$c .= '<tr><td>City/State/Zip:</td><td>'.fetch_edit('city', $r['city'])
				.'</td><td>'.fetch_edit('state', $r['state'], $r['customer_id'])
				.'</td><td>'.fetch_edit('zip', $r['zip']).'</td></tr>';
	//=====================
	
	//====Phone Fax====
	$c .= '<tr><td>Phone/Fax:</td><td>'.fetch_edit('phone', $r['phone'])
				.'</td><td colspan=2>'.fetch_edit('fax', $r['fax']).'</td></tr>';
	//=====================
	
	//====Contact Name====
	$c .= '<tr><td>Contact&nbsp;Name:</td><td>'.fetch_edit('contact_name', $r['contact_name']).'</td></tr>';
	//=====================

	//====Contact Email====
	$c .= '<tr><td>Email:</td><td>'.fetch_edit('email', $r['email']).'</td></tr>';
	//=====================
	
	$c .= "</table></fieldset>";
	//=====================
	//=====================
	
	//====Billing====
	//===============
	$c .= "<fieldset><legend>Billing</legend><table>";

	//====Billing Name====
	$c .= '<tr><td>Attention:</td><td>'.fetch_edit('billing_attention', $r['billing_attention']).'</td></tr>';
	//=====================
	
	//====Billing Address====
	$c .= '<tr><td>Address:</td><td>'.fetch_edit('billing_address', $r['billing_address']).'</td></tr>';
	//=====================
	
	//====Billing City State Zip====
	$c .= '<tr><td>City/State/Zip:</td><td>'.fetch_edit('billing_city', $r['billing_city'], $r['customer_id'])
				.'</td><td>'.fetch_edit('billing_state', $r['billing_state'], $r['customer_id'])
				.'</td><td>'.fetch_edit('billing_zip', $r['billing_zip']).'</td></tr>';
	//=====================
	
	//====Billing Fax====
	$c .= '<tr><td>Phone/Fax:</td><td>'.fetch_edit('billing_phone', $r['billing_phone'], $r['customer_id'])
				.'</td><td colspan=2>'.fetch_edit('billing_fax', $r['billing_fax']).'</td></tr>';
	//=====================
	
	//====Billing Name====
	$c .= '<tr><td>Contact&nbsp;Name:</td><td>'.fetch_edit('billing_contact_name', $r['billing_contact_name']).'</td></tr>';
	//=====================

	//====Billing Email====
	$c .= '<tr><td>Email:</td><td>'.fetch_edit('billing_email', $r['billing_email']).'</td></tr>';
	//=====================
	
	$c .= "</table></fieldset>";
	//=====================
	//=====================
	
	//====Misc====
	$c .= "<fieldset><table>";
	$c .= '<tr><td>Status:</td><td>'.fetch_edit('status', $r['status']).'</td>';
	$c .= '<td>Lead Source:</td><td>'.fetch_edit('lead_source', $r['lead_source']).'</td></tr>';
	$c .= '<tr><td>Account Status:</td><td>'.fetch_edit('account_status', $r['account_status']).'</td>';
	$c .= '<td>Account Owner:</td><td>'.fetch_edit('acct_owner', $r['acct_owner']).'</td></tr>';
	$c .= "</table></fieldset>";
	//=============
	
	//====Warehouses====
	$c .= "<fieldset><legend>Warehouse List</legend>";
	$c .= "<form>
<input type=button value=\"New\" onClick=\"javascript:popUp('http://".HTTP_ROOT."/?page=warehouse&action=New&customer_id=$_REQUEST[customer_id]&popup')\">
</form>";
	//$c .= "<a href='http://".HTTP_ROOT."/?page=warehouse&action=New&customer_id=$r[customer_id]'>New</a><br>";
	$c .= get_warehouses();
	$c .= "</fieldset>";
	//==================
	
	$c .= "</td><td valign='top'>";
	//====End Main====
	
	//====Notes====
	$c .= "<fieldset><legend>Notes</legend>";
	$c .= "<form>
<input type=button value=\"New\" onClick=\"javascript:popUp('http://".HTTP_ROOT."/?page=customer_notes&action=New&customer_id=$_REQUEST[customer_id]&popup')\">
</form>";
	//$c .= "<a href='http://".HTTP_ROOT."/?page=customer_notes&action=New&customer_id=$_REQUEST[customer_id]'>New</a>";
	//$c .= get_notes();
	$c .= "<div id='customer_notes'>
			</div>
				<script>
			get_portal('customer_notes');
		</script>";
	$c .= "</fieldset>";
	//=============
	
	//====Loads====
	$c .= "<fieldset><legend>Loads</legend>";
	$c .= get_loads();
	$c .= "</fieldset>";
	//=============
	$c .= "</td></tr></table>";
	
	return $c;
}

function get_customer_menu()
{
	global $search_edit;
	$c = "<table><tr>";
	$c .= "<td><a href='http://".HTTP_ROOT."/?page=customer&action=$search_edit'><div class='menu'>Search</div></a></td>";
	$c .= "<td><a href='http://".HTTP_ROOT."/?page=customer'><div class='menu'>All Accounts</div></a></td>";
	$c .= "<td><a href='http://".HTTP_ROOT."/?page=customer&action=New'><div class='menu'>New</div></a></td>";
	
	$c .= "</tr></table>";
	return $c;
}

function fetch_edit($name, $value)
{
	global $t;
	$c =& $t->get_column($name);
	$pk_obj =& $t->get_primary_key();
	$pk_name = $pk_obj->get_name();
	
	$o = $c->get_edit_html($value);
	$o->set_id("action=update&table=$_REQUEST[page]&".$pk_name."=".$_REQUEST[$pk_name]."&".$name."=");
	$o->add_attribute('onchange', 'db_save(this.id, this.value);');
	//$o->add_attribute('onchange', "alert('bugger');");
	$script = "<script>
	var i = document.getElementById('$name');
	i.setReturnFunction(onchange);
	</script>";
	
	return $o->render();
}
function fetch_edit_old($name, $value)
{
	global $t;
	$c =& $t->get_column($name);
	$pk_obj =& $t->get_primary_key();
	$pk_name = $pk_obj->get_name();
	
	$o = $c->get_edit_html($value);
	$o->set_id("table=$_REQUEST[page]&".$pk_name."=".$_REQUEST[$pk_name]."&".$name."=");
	$o->add_attribute('onchange', 'db_save(this.id, this.value)');
	
	return $o->render();
}
?>