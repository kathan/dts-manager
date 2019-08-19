<?php
//ini_set('display_errors', 'On');//Debug only
global $search_edit;
global $search;
$search = 'Search';
$search_edit = 'search_edit';
require_once"includes/global.php";
require_once"includes/auth.php";

if(logged_in())
{
	echo "<title>".SITE_NAME."-Loads</title>";
	echo "<head><script src='sortable.js'></script>
		<script src='db_save.js.php'></script>
		<script>
		function get_portal(table)
		{
			var d = document.getElementById(table);
			d.innerHTML = 'Loading '+table;
			//alert(document.URL);
			var portal = getFromURL('http://".HTTP_ROOT."/?page='+table+'&load_id=".safe_get($_REQUEST['load_id'])."&action=portal);
			//alert(\"====\"+portal);
			d.innerHTML = '';
			d.innerHTML = portal;
		}
		function column_updated(t)
		{
			//alert(t.name);
			if (t.name == 'customer_total' || t.name == 'carrier_total')
			{
				//alert('score');
				update_gp();
			}
		}
		function update_gp()
		{
			var gp = document.getElementById('gp');
			var gpp = document.getElementById('gpp');
			
			var cust_total = document.getElementsByName('customer_total');
			var car_total = document.getElementsByName('carrier_total');
			//alert(parseInt(car_total[0].value));
			var gp_val = (parseInt(cust_total[0].value) - parseInt(car_total[0].value))||0;
			gp.innerHTML = gp_val;
			if(Math.round(gp_val*100) != 0)
			{
				gpp.innerHTML = Math.round((gp_val*100)/ parseInt(cust_total[0].value))+'%';
			}else{
				gpp.innerHTML = 0+'%';
			}
		}
		
		</script>";
	require_once"includes/table.php";
	require_once"includes/column.php";
	
	
	/*$t->add_table_params('page', 'load');
	
	require_once"includes/hidden_input.php";
	$i = new hidden_input('page', 'load');
	$t->add_other_inputs($i);*/
	echo get_load_menu();
	//print_r($_REQUEST);
	switch(get_action())
	{
		case 'Add':
			echo do_add();
			break;
	}
	echo "<table width='100%' border=0 class='content load_content'><tr><td width='1%'>";
	switch(get_action())
	{
		case $search_edit:
			echo '<center><h2>Load Search</h2>';
			echo "Use % as a wildcard character";
			echo get_search();
			break;
		case $search:
			echo '<center><h2>Load Search Results</h2>';
			echo get_all();
			break;
		case 'Edit':
			echo '<center><h2>Load Edit</h2>';
			echo get_load_edit();
			break;
		case 'New':
			echo '<center><h2>New Load</h2>';
			echo get_new();
			break;
		case 'all':
			echo '<center><h2>Load List</h2>';
			echo get_all();
			break;
		default:
			echo load_board();
			break;
	}
	echo "</td></tr></table>
	<script>
	update_gp();
	</script>";
}

function do_add()
{
	$t = new table("load");
	$t->add();
	return $t->feedback;
}

function get_all()
{
	require_once"includes/portal.php";
	$q = new portal("	SELECT 	load_id,
								(SELECT name FROM customer c WHERE c.customer_id = l.customer_id) Customer,
								(SELECT CONCAT(w.state,' ',w.city) FROM warehouse w WHERE w.warehouse_id = l.origin) origin,
								(SELECT CONCAT(w.state,' ',w.city) FROM warehouse w WHERE w.warehouse_id = l.dest) dest
						FROM `LOAD` l");

	$q->set_table('load');
	$q->set_primary_key('load_id');
	return $q->render();
}

function get_search()
{
	global $search;
	require_once("includes/submit_input.php");
	$t = new table("load");
	require_once"includes/hidden_input.php";
	$i = new hidden_input('page', 'load');
	$t->add_other_inputs($i);
	
	$ob =& $t->get_column('carrier_id');
	$ob->set_parent_label_column('name');
	
	$ob =& $t->get_column('order_by');
	$ob->set_parent_label_column('username');
	
	$ob =& $t->get_column('origin');
	$ob->set_parent_label_column('name');
	
	$ob =& $t->get_column('dest');
	$ob->set_parent_label_column('name');
	
	$ob =& $t->get_column('customer_id');
	$ob->set_parent_label_column('name');
	
	$si = new submit_input($search, 'action');
	$f =& $t->get_form();
	$f->set_get();
	$t->set_submit_input($si);
	return $t->_render_edit();
}


function get_new()
{
	$t = new table("load");
	$t->omit_column('load_id');
	$t->omit_column('problem');
	$t->omit_column('solution');
	$t->omit_column('bounce');
	$t->omit_column('cancelled');
	
	$col =& $t->get_column('rating');
	$rating_list = Array('Standard' => 'Standard', 'Expedited' => 'Expedited');
	$col->set_value_list($rating_list);
	
	$script = "
		var i = document.getElementById('booked');
		i.setReturnFunction('onchange');
	";
	
	$col =& $t->get_column('trailer_type');
	$trailer_type_list = Array('Van' => 'Van', 'Refrig' => 'Refrig', 'Flatbed' => 'Flatbed', 'Rail' => 'Rail');
	$col->set_value_list($trailer_type_list);
	
	$ob =& $t->get_column('origin');
	$ob->set_parent_label_column('name');
	
	$ob =& $t->get_column('dest');
	$ob->set_parent_label_column('name');
	
	$ob =& $t->get_column('order_by');
	$ob->set_parent_label_column('username');
	
	$ob =& $t->get_column('customer_id');
	$ob->set_parent_label_column('name');
	$ob =& $t->get_column('carrier_id');
	$ob->set_parent_label_column('name');
	$script = "<script>$script</script>";
	return $script.$t->render();
}

function get_carriers()
{
	require_once"includes/table.php";
	$t = new table("load_carrier");
	$t->hide_delete();
	$t->add_table_params('page', 'load_carrier');

	return $t->_render_list();
}

function get_warehouses()
{
	
}

function get_load_edit()
{
	//global $t;
	$t = new table("load");
	$t->hide_delete();
	$t->hide_column('load_id');
	
	$ob =& $t->get_column('order_by');
	$ob->set_parent_label_column('username');
	$ob =& $t->get_column('origin');
	$ob->set_parent_label_column('name');
	$ob =& $t->get_column('dest');
	$ob->set_parent_label_column('name');
	$r = $t->get_row($_REQUEST['load_id']);
	
	$t->hide_delete();
	//$t->hide_column('load_id');
	$t->add_table_params('page', 'load');
	
	require_once"includes/hidden_input.php";
	$i = new hidden_input('page', 'load');
	$t->add_other_inputs($i);
	
	$c = "<table><tr>";
		$c .= "<td valign='top'>";
	//====Load ID====
	$c .= "<fieldset><legend>Load ID</legend>";
	$c .= "<table width='100%' border=0>";
	$c .= "<tr><td>Load ID</td><td>$r[load_id]</td></tr>";
	$c .= '<tr><td>Order By</td><td>'.fetch_edit($t, 'order_by', $r['order_by']).'</td></tr>';
	$c .= "</table>";
	$c .= "</fieldset>";
	//==================
		$c .= "</td>";
		$c .= "<td valign='top'>";
	//====Rating====
	$c .= "<fieldset><legend>Rating</legend>";
	$c .= "<table width='100%' border=0>";
	$col =& $t->get_column('rating');
	$rating_list = Array('Standard' => 'Standard', 'Expedited' => 'Expedited');
	$col->set_value_list($rating_list);
	$c .= '<tr><td>Rating Code</td><td>'.fetch_edit($t, 'rating', $r['rating']).'</td><tr>';
	$c .= '<tr><td>'.fetch_edit($t, 'cancelled', $r['cancelled']).'</td><td>Cancel</td></tr>';
	$c .= "</table>";
	$c .= "</fieldset>";
	//==================
		$c .= "</td>
				<td valign='top'>";
	//====Problem====
	$c .= "<fieldset><legend>Problem</legend>";
	$c .= "<table width='100%' border=0>";
	$c .= '<tr><td>Problem</td><td>'.fetch_edit($t, 'problem', $r['problem']).'</td><tr>';
	$c .= '<tr><td>Solution</td><td>'.fetch_edit($t, 'solution', $r['solution']).'</td></tr>';
	$c .= "</table>";
	$c .= "</fieldset>";
	//==================
		$c .= "	</td>
			</tr>
			<tr>
			<td colspan=3>";
	
	//====Customer====
	$c .= "<fieldset><legend>Customer</legend>";
	$c .= "<table><tr>";
	$col =& $t->get_column('trailer_type');
	$trailer_type_list = Array('Van' => 'Van', 'Refrig' => 'Refrig', 'Flatbed' => 'Flatbed', 'Rail' => 'Rail');
	$col->set_value_list($trailer_type_list);
	$c .= "<td>Trailer Type</td><td>".fetch_edit($t, 'trailer_type', $r['trailer_type'])."</td>";
	$c .= "<td>Length (inches)</td><td>".fetch_edit($t, 'length', $r['length'])."</td>";
	$c .= "<td>Size</td><td>".fetch_edit($t, 'size', $r['size'])."</td></tr>";
	$c .= "<tr><td>Pallets</td><td>".fetch_edit($t, 'pallets', $r['pallets'])."</td>";
	$c .= "<td>Weight (lbs.)</td><td>".fetch_edit($t, 'weight', $r['weight'])."</td>";
	$c .= "<td>Class</td><td>".fetch_edit($t, 'class', $r['class'])."</td>";
	$c .= "</tr></table>";
	$c .= get_customer($r['customer_id']);
	$c .= "</fieldset>";
	//==================
	$c .= "</td></tr><tr><td colspan=3>";
	//====Money====
	$c .= "<fieldset><legend>Money</legend>";
	$c .= "<table width='100%' border=0>";
	$c .= '<tr><td>Customer Total</td><td>'.fetch_edit($t, 'customer_total', $r['customer_total']).'</td>';
	
	$c .= '<td>Carrrier Total</td><td>'.fetch_edit($t, 'carrier_total', $r['carrier_total']).'</td>';
	//$gp = $r['customer_total'] - $r['carrier_total'];
	$c .= "<td>GP</td><td style='border:1px solid black'><div id='gp'></div></td>";
	//if(round($gp*100) != 0)
	//{
	//	$gpp = round(($gp*100)/ $r['customer_total']);
	//}else{
	//	$gpp = 0 ;
	//}
	$c .= "<td>GPP</td><td style='border:1px solid black'><div id='gpp'>%</div></td></tr>";
	$c .= "</table>";
	$c .= "</fieldset>";
	//==================
	$c .= "</td></tr><tr><td colspan=3>";
	//====Carriers====
	$c .= "<fieldset><legend>Carriers</legend>";
	$c .= get_carriers_selection();
	$c .= "<div id='load_carrier'>
		</div>
		<script>
			get_portal('load_carrier');
		</script>";
	
	//$c .= get_carriers();
	$c .= "</fieldset>";
	//================
	//====Schedule====
	$c .= "<fieldset>
	<legend>Schedule</legend>";
	$c .= "
	<table>
		<tr>";
	$c .= "
			<td>Ordered</td>
			<td>".fetch_edit($t, 'ordered', $r['ordered'])."</td>";
	$c .= "
			<td>Booked</td>
			<td>".fetch_edit($t, 'booked', $r['booked'])."</td>";
	$c .= "
			<td>Checked-In</td>
			<td>".fetch_edit($t, 'checked_in', $r['checked_in'])."</td>";
	$c .= "
			<td>Loaded</td>
			<td>".fetch_edit($t, 'loaded', $r['loaded'])."</td>";
	$c .= "
			<td>Delivered</td>
			<td>".fetch_edit($t, 'delivered', $r['delivered'])."</td>";
	$c .= "
		</tr>
	</table>
</fieldset>";
	//==================
	$c .= "</td></tr><tr><td colspan=3>";
	//====Warehouses====
	$c .= "<fieldset><legend>Warehouses</legend>";
	//$c .= get_warehouse_selection();
	/*$c .= "<div id='load_warehouse'>
		</div>";
	/*$c .="
		<script>
			get_portal('load_warehouse');
		</script>";
	//$c .= get_warehouses();//*/
	$c .= "Origin:".fetch_edit($t, 'origin', $r['origin']);
	$c .= "Dest:".fetch_edit($t, 'dest', $r['dest']);
	$c .= "</fieldset>";
	//==================
	$c .= "</td></tr></table>";
	
	return $c;
}

function get_customer($customer_id)
{
	set_post('customer_id', $customer_id);
	$t = new table("customer");
	$t->omit_all_columns();
	$t->get_row();
	$t->add_virtual_column('customer_id', 'id');
	$t->add_virtual_column('name', 'name');
	$t->add_virtual_column('city', 'city');
	$t->add_virtual_column('state', 'state');
	$t->hide_delete();
		
	return $t->_render_list();
}

function fetch_edit($t, $name, $value)
{
	//global $t;
	$c =& $t->get_column($name);
	$pk_obj =& $t->get_primary_key();
	$pk_name = $pk_obj->get_name();
	
	$o = $c->get_edit_html($value);
	$o->set_id("action=update&table=$_REQUEST[page]&".$pk_name."=".$_REQUEST[$pk_name]."&".$name."=");
	$o->add_attribute('onchange', 'db_save(this.id, this.value);column_updated(this);');
	//$o->add_attribute('onchange', "alert('bugger');");
	$script = "<script>
	var i = document.getElementById('$name');
	i.setReturnFunction(onchange);
	</script>";
	
	return $o->render();
}

function load_board()
{
	if (isset($_REQUEST['day']))
	{
		$cur_day = $_REQUEST['day'];
	}else
	{
		$cur_day = date("d");
	}
	
	if (isset($_REQUEST['month']))
	{
		$cur_month = $_REQUEST['month'];
	}else
	{
		$cur_month = date("m");
	}
	if (isset($_REQUEST['year']))
	{
		$cur_year = $_REQUEST['year'];
	}else
	{
		$cur_year = date("Y");
	}
	
	$cur_date_str = $cur_day . " " . get_month_name($cur_month) . " " . $cur_year;
	$prev_date_str = $cur_date_str . " -1 day";
	$next_date_str = $cur_date_str . " +1 day";
	$db_date = $cur_year.'-'.$cur_month.'-'.$cur_day;
	$next_date = strtotime($next_date_str);
	$prev_date = strtotime($prev_date_str);
	$prev_day = date("d", $prev_date);
	$prev_month = date("m", $prev_date);
	$prev_year = date("Y", $prev_date);
	
	$next_day = date("d", $next_date);
	$next_month = date("m", $next_date);
	$next_year = date("Y", $next_date);
	//echo $db_date;
	$c = "
		<center>
			<h2>Load Board</h2>
		<table>
			<tr>
				<td>
					<a href='http://".HTTP_ROOT."/?page=load&day=$prev_day&month=$prev_month&year=$prev_year'>Previous Day</a>
				</td>
				<td>
					<center>
						Viewing Date: $cur_month/$cur_day/$cur_year<br>
					</center>
				</td>
				<td align='right'>
					<a href='http://".HTTP_ROOT."/?page=load&day=$next_day&month=$next_month&year=$next_year'>Next Day</a>
				</td>
			</tr>
			<tr>
				<td colspan=3>
		<table>
			<tr>
				<td style='border:1px solid black'>
					Ordered
				</td>
				<td style='border:1px solid black'>
					Booked
				</td>
				<td style='border:1px solid black'>
					Checked In
				</td>
				<td style='border:1px solid black'>
					Loaded
				</td>
				<td style='border:1px solid black'>
					Delivered
				</td>
			</tr>
			<tr>
				<td style='border:1px solid black' valign='top'>"
	.get_loads($db_date, 'ordered').
				"</td>
				<td style='border:1px solid black' valign='top'>"
	.get_loads($db_date, 'booked').
				"</td>
				<td style='border:1px solid black' valign='top'>"
	.get_loads($db_date, 'checked_in').
			"	</td>
				<td style='border:1px solid black' valign='top'>"
	.get_loads($db_date, 'loaded').
			"	</td>
				<td style='border:1px solid black' valign='top'>"
	.get_loads($db_date, 'delivered').
			"	</td>
			</tr>
		</table>
		</td>
		</tr>
		</table>
		</center>";

	return $c;
}

function get_loads($date, $type)
{
	require_once'includes/portal.php';
	$q = new portal();
	$q->set_sql("	SELECT 	load_id,
								(SELECT CONCAT(w.state,' ',w.city) FROM warehouse w WHERE w.warehouse_id = load.origin) origin,
								(SELECT CONCAT(w.state,' ',w.city) FROM warehouse w WHERE w.warehouse_id = load.dest) dest
						FROM `load`
						WHERE $type = '$date'");
	$q->set_table('load');
	$q->set_primary_key('load_id');
	return $q->render();
}
function get_loads_old($date, $type)
{
	$t = new table("load");
	$t->hide_delete();
	//$t->hide_menu();
	$t->omit_all_columns();
	set_post($type, $date);
	//echo $date."<br>";
	$t->add_virtual_column("(SELECT CONCAT(w.state,' ',w.city) FROM warehouse w WHERE w.warehouse_id = load.origin)", 'origin location');
	$t->add_virtual_column("(SELECT CONCAT(w.state,' ',w.city) FROM warehouse w WHERE w.warehouse_id = load.dest)", 'dest location');
	
	$l = $t->_render_list();
	unset_post($type);
	return $l;
}

function get_load_menu()
{
	global $search_edit;
	global $search;
	$c = "<table><tr>";
	$c .= "<td><a href='http://".HTTP_ROOT."/?page=load'><div class='menu'>Load Board</div></a></td>";
	$c .= "<td><a href='http://".HTTP_ROOT."/?page=load&action=$search_edit'><div class='menu'>$search</div></a></td>";
	$c .= "<td><a href='http://".HTTP_ROOT."/?page=load&action=all'><div class='menu'>All Loads</div></a></td>";
	$c .= "<td><a href='http://".HTTP_ROOT."/?page=load&action=New'><div class='menu'>New Load</div></a></td>";
	$c .= "</tr></table>";
	return $c;
}
function get_month_name($dayInt)
{
	$monthAry = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	$dayInt = ltrim($dayInt, "0");
	return $monthAry[$dayInt];
}

function get_carriers_selection()
{
	require_once("includes/select_input.php");
	$sql = "SELECT * FROM carrier";
	$r = db_query($sql);
	$select = new select_input("carrier_id", "carrier_id", "name", $r);
	$select->set_id("table=load_carrier&load_id=".$_REQUEST['load_id']."&carrier_id=");

	$select->add_attribute('onchange', 'db_save(this.id, this.value); get_portal("load_carrier")}');
	return $select->render();
}

function get_warehouse_selection()
{
	require_once("includes/select_input.php");
	$sql = "SELECT * FROM warehouse";
	$r = db_query($sql);
	$select = new select_input("warehouse_id", "warehouse_id", "name", $r);
	$select->set_id("table=load_warehouse&load_id=".$_REQUEST['load_id']."&warehouse_id=");

	$select->add_attribute('onchange', 'db_save(this.id, this.value); get_portal("load_warehouse")}');
	return $select->render();
}
?>