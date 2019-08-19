<?php
echo "<title>".SITE_NAME."-Warehouse</title>";
require_once"includes/global.php";
require_once"includes/auth.php";
if(logged_in())
{
	if(!isset($_REQUEST['popup']))
	{
		echo get_warehouse_menu();
	}
	require_once"includes/table.php";
	$t = new table("warehouse");
	$t->hide_delete();
	$t->hide_column('warehouse_id');
	$t->set_label('customer_id', 'Customer');
	$t->add_table_params('page', 'warehouse');
	
	require_once"includes/hidden_input.php";
	$i = new hidden_input('page', 'warehouse');
	$t->add_other_inputs($i);
	
	$c =& $t->get_column('customer_id');
	$c->set_parent_label_column('name');
	switch(get_action())
	{
		case $t->add_str:
			
			
			echo "<script>
			function refresh_close()
			{
				window.opener.get_portal('warehouse');
				window.close();
			}
					window.onload = refresh_close;
				</script>";
			$t->render();
			break;
		case $t->new_str:
			echo '<center><h2>New Warehouse</h2>';
			echo new_warehouse($t);
			break;
		default:
			echo '<center><h2>Warehouse List</h2>';
			echo $t->render();
			break;
	}
	
}

function get_warehouse_menu()
{
	//global $search_edit;
	$c = "<table><tr>";
//	$c .= "<td><a href='http://".HTTP_ROOT."/?page=warehouse&action=$search_edit'><div class='menu'>Search</div></a></td>";
	//$c .= "<td><a href='http://".HTTP_ROOT."/?page=warehouse'><div class='menu'>All Warehouses</div></a></td>";
	$c .= "<td><a href='http://".HTTP_ROOT."/?page=warehouse&action=New'><div class='menu'>New</div></a></td>";
	
	$c .= "</tr></table>";
	return $c;
}

function new_warehouse($t)
{
	$c ='<script>
			function submit_close()
			{
				var f = document.getElementById("new_form");
				f.submit();
			}
			</script>';
	$c .= '<table><tr>';
	$c .= "<form id='new_form' onsubmit=submit_close'' method='post'>";
	$c .= "<input type='hidden' name='page' value='warehouse'>";
	$c .= "<input type='hidden' name='action' value='Add'>";

	$c .= '<tr><td>Customer</td><td>'.fetch_edit($t, 'customer_id', $_REQUEST['customer_id']).'</td></tr>';
	$c .= '<tr><td>Name</td><td>'.fetch_edit($t, 'name').'</td></tr>';
	$c .= '<tr><td>Address</td><td>'.fetch_edit($t, 'address').'</td></tr>';
	$c .= '<tr><td>City</td><td>'.fetch_edit($t, 'city').'</td></tr>';
	$c .= '<tr><td>State</td><td>'.fetch_edit($t, 'state').'</td></tr>';
	$c .= '<tr><td>Zip</td><td>'.fetch_edit($t, 'zip').'</td></tr>';
	$c .= '<tr><td>Phone</td><td>'.fetch_edit($t, 'phone').'</td></tr>';
	$c .= '<tr><td>Fax</td><td>'.fetch_edit($t, 'fax').'</td></tr>';
	$c .= '<tr><td>Notes</td><td>'.fetch_edit($t, 'notes').'</td></tr>';
	$c .= '<tr><td>Directions</td><td>'.fetch_edit($t, 'directions').'</td></tr>';
	$c .= '<tr><td>Open Time</td><td>'.fetch_edit($t, 'open_time').'</td></tr>';
	$c .= '<tr><td>Close Time</td><td>'.fetch_edit($t, 'close_time').'</td></tr>';
	$c .= '<tr><td><input type="button" onclick="submit_close()" value="Save"></td></tr>';
	$c .= "</form>";
	$c .= '</tr></table>';
	return $c;
}
function fetch_edit($t, $name, $value=null)
{
	//global $t;
	$c =& $t->get_column($name);
	$pk_obj =& $t->get_primary_key();
	$pk_name = $pk_obj->get_name();
	
	$o = $c->get_edit_html($value);
	//$o->set_id("action=update&table=$_REQUEST[page]&".$pk_name."=".$_REQUEST[$pk_name]."&".$name."=");
	
	return $o->render();
}
?>