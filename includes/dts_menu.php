<?php
require_once("auth.php");
require_once("menu.php");
require_once("menu_item.php");
require_once('includes/select_input.php');
require_once('includes/hidden_input.php');
require_once('includes/html_form.php');

$menu = new menu();
if(logged_in()){
    $load = new menu_item("?page=load", "<img src='".App::getImgRoot()."/package.gif' border='0' width='40px' alt='' /><br />Loads");
    $menu->add_item($load);

    $customer = new menu_item("?page=customer", "<img src='".App::getImgRoot()."/accounts.gif' border='0' width='40px' alt='' /><br />Customers");
    $menu->add_item($customer);

    $carrier = new menu_item("?page=carrier", "<img src='".App::getImgRoot()."/truck.gif' border='0' width='40px' alt='' /><br />Carriers");
    $menu->add_item($carrier);

    $warehouse = new menu_item("?page=warehouse", "<img src='".App::getImgRoot()."/warehouse.gif' border='0' width='40px' alt='' /><br />Warehouses");
    $menu->add_item($warehouse);

    $lanes = new menu_item("/?page=lanes", "<img src='".App::getImgRoot()."/lanes.gif' border='0' width='40px' alt='' /><br />Lanes");
    $menu->add_item($lanes);

    if(logged_in_as('admin')){
	$users = new menu_item("?page=users", "<img src='".App::getImgRoot()."/users.png' border='0' width='40px' alt='' /><br />Users");
	$menu->add_item($users);
		
	$reports = new menu_item("?page=reports", "<img src='".App::getImgRoot()."/reports.jpg' border='0' width='40px' alt='' /><br />Reports");
	$menu->add_item($reports);
    }
    $user = new menu_item("?page=users&action=edit&user_id=".get_user_id(), "logged&nbsp;in&nbsp;as&nbsp;".$_COOKIE[COOKIE_USERNAME]);
    $menu->add_item($user);
	
    $logout = new menu_item("?page=logout", "Log out");
    $menu->add_item($logout);
	
    $options = [
        'load'=>'Load'
	, 'customer'=>'Customer'
	, 'carrier'=>'Carrier'
	, 'warehouse'=>'Warehouse'
    ];
    $new = new select_input('page', null, null, $options);
    $new->set_label('New');
    $new->set_id('page');
    $new->add_attribute('onchange', 'make_new();');
	
    $h = new hidden_input('action', 'New');
	
    $f = new html_form();
    $f->set_get();
    $f->add_input($h);
    $f->add_input($new);
	
    $f->in_table = false;
    $menu->add_item($f);
}else{
    $user = new menu_item("?page=login", "Log In");
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
        