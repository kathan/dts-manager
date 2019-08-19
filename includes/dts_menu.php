<?php
require_once("auth.php");
require_once("menu.php");
require_once("menu_item.php");
$menu = new menu();
if(logged_in()){
	

	//$home = new menu_item("http://".HTTP_ROOT."/?", "<img src='http://".IMG_ROOT."/home.gif' border='0' width='40px'><br />Home");
	//$menu->add_item($home);

	$load = new menu_item("http://".HTTP_ROOT."/?page=load", "<img src='http://".IMG_ROOT."/package.gif' border='0' width='40px' alt='' /><br />Loads");
	$menu->add_item($load);

	$customer = new menu_item("http://".HTTP_ROOT."/?page=customer", "<img src='http://".IMG_ROOT."/accounts.gif' border='0' width='40px' alt='' /><br />Customers");
	$menu->add_item($customer);

	$carrier = new menu_item("http://".HTTP_ROOT."/?page=carrier", "<img src='http://".IMG_ROOT."/truck.gif' border='0' width='40px' alt='' /><br />Carriers");
	$menu->add_item($carrier);

	$warehouse = new menu_item("http://".HTTP_ROOT."/?page=warehouse", "<img src='http://".IMG_ROOT."/warehouse.gif' border='0' width='40px' alt='' /><br />Warehouses");
	$menu->add_item($warehouse);

	$lanes = new menu_item("http://".HTTP_ROOT."/?page=lanes", "<img src='http://".IMG_ROOT."/lanes.gif' border='0' width='40px' alt='' /><br />Lanes");
	$menu->add_item($lanes);

	//$contacts = new menu_item("http://".HTTP_ROOT."/?page=contacts", "<img src='http://".IMG_ROOT."/contacts.gif' border='0' width='40px'><br />Contacts");
	//$menu->add_item($contacts);

	if(logged_in_as('admin')){
		$users = new menu_item("http://".HTTP_ROOT."/?page=users", "<img src='http://".IMG_ROOT."/users.png' border='0' width='40px' alt='' /><br />Users");
		$menu->add_item($users);
		
		$reports = new menu_item("http://".HTTP_ROOT."/?page=reports", "<img src='http://".IMG_ROOT."/reports.jpg' border='0' width='40px' alt='' /><br />Reports");
		$menu->add_item($reports);
	}
	$user = new menu_item("http://".HTTP_ROOT."/?page=users&action=edit&user_id=".get_user_id(), "logged&nbsp;in&nbsp;as&nbsp;".$_COOKIE[COOKIE_USERNAME]);
	$menu->add_item($user);
	
	$logout = new menu_item("http://".HTTP_ROOT."/?page=logout", "Log out");
	$menu->add_item($logout);
	
	require_once('includes/select_input.php');
	$options = ['load'=>'Load'
					, 'customer'=>'Customer'
					, 'carrier'=>'Carrier'
					, 'warehouse'=>'Warehouse'
					];
	$new = new select_input('page', null, null, $options);
	$new->set_label('New');
	$new->set_id('page');
	$new->add_attribute('onchange', 'make_new();');
	
	require_once('includes/hidden_input.php');
	$h = new hidden_input('action', 'New');
	
	require_once('includes/html_form.php');
	$f = new html_form();
	$f->set_get();
	$f->add_input($h);
	$f->add_input($new);
	
	$f->in_table = false;
	$menu->add_item($f);
	
}else{
	$user = new menu_item("http://".HTTP_ROOT."/?page=login", "Log In");
	$menu->add_item($user);
}
	echo"
			<script type='text/javascript'>
				function make_new()
				{
					var p = document.getElementById('page');
					
					if(p.value)
					{
						p.form.submit();
					}
				}
			</script>";
	echo $menu->render();
	
	/*$new = new menu_item("", "<form method='GET'>
				<input type='hidden' name='action' value='New'>
				<select id='page' name='page' onchange='make_new();'>
					<option>New...</option>
					<option value='load'>Load</option>
					<option value='customer'>Customer</option>
					<option value='carrier'>Carrier</option>
				</select>
			</form>
	*/
	
?>