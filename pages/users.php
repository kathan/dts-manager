<?php
ini_set('display_errors', 'On');//Debug only

require_once "includes/global.php";
require_once "includes/auth.php";
require_once "includes/html.php";
require_once "includes/dts_table.php";
require_once("includes/Template.php");
require_once("includes/DB_Table.php");
require_once"includes/portal.php";
$post_action = '';


//print_r($_REQUEST);

class users extends dts_table{
	function __construct(){
		parent::__construct('users');
	}
function process_users(){
	global $feedback;
	$content = '';
	
	if(isset($_POST['action'])){
		if(isset($_POST['object'])){
			switch ($_POST['object']){
				case 'regions':
					switch ($_POST['action']){
						case "Add":
							$this->add_regions($_POST['user_id'], $_POST['unlinkedregions']);
							break;
						case "Remove":
							$this->remove_regions($_POST['user_id'], $_POST['linkedregions']);
							break;
					}
						break;
				case 'contacts':
					switch ($_POST['action']){
						case "Add":
							$this->add_contacts($_POST['user_id'], $_POST['unlinkedcontacts']);
							break;
						case "Remove":
							$this->remove_contacts($_POST['user_id'], $_POST['linkedcontacts']);
							break;
					}
					break;
				case 'groups':
					switch ($_POST['action']){
						case "Add":
							$this->add_groups($_POST['user_id'], $_POST['unlinkedgroups']);
							break;
						case "Remove":
							$this->remove_groups($_POST['user_id'], $_POST['linkedgroups']);
							break;
					}
					break;
			}
		}else if($_POST['action'] == "Save"){
			$_POST['active'] = checkbox_to_binary($_POST['active']);
			$user_id = $this->save_user();
		}
	}
	//echo "user_id: $user_id";
	if(!isset($user_id) && isset($_GET['user_id'])){
		$user_id = $_GET['user_id'];
	}
	isset($_GET['action']) ? $action = $_GET['action'] : $action = '';
	switch ($action){
		case "Cancel":
			$feedback .= "User cancelled.";
			$content .= $this->show_list();
			break;
		case "edit":
			if(isset($user_id)){
				$user = $this->get_user($user_id);
			}
			$content .= $this->edit_user($user);
			break;
		case "cust":
			$content .= $this->get_customers();
			break;
		case "portal":
			if($_GET['portal'] == 'cust_owner_notes'){
				$content .= $this->get_cust_notes($_GET['customer_id']);
			}
			break;
		default:
			$content .= $this->show_list();
			break;
	}
	return $content;
}
	
function check_user_form()
{
}

function save_user(){
	global $feedback;
	//echo 'active:'.$_POST['active'];
	if (logged_in_as('super admin') || logged_in_as($_COOKIE[COOKIE_USERNAME])){
		$t = new DB_Table('users');
		if(isset($_REQUEST['user_id'])){
			
			if(isset($_POST['password1']) && isset($_POST['password2'])){
				
				if(($_POST['password1'] == $_POST['password2'])){
					if($_POST['password1'] != '' && $_POST['password2'] != ''){
						$_POST['password'] = $_POST['password1'];
					}
					if($t->update($_POST)){
						$feedback .= "User Updated</br>";
					}else{
						$feedback .= $t->error_str."</br>";
						$feedback .= $t->feedback."</br>";
					}
				}else{
					$feedback .= "Passwords do not match.</br>";
				}
			}
			
		}else{
			if($_POST['password1'] && $_POST['password2']){
				if(($_POST['password1'] == $_POST['password2'])){
					$_POST['password'] = $_POST['password1'];
					if($t->insert($_POST)){
						$feedback .= "User Added</br>";
						header('Location: ?page='.basename(__FILE__, '.php').'&action=edit&user_id='.$t->last_id);
					}else{
						$feedback .= $t->error_str."</br>";
						$feedback .= $t->feedback."</br>";
					}
				}else{
					$feedback .= "Passwords do not match.</br>";
				}
			}
		}
	}else{
		noAuth();
	}
}

function add_groups($user_id, $group_ids)
{
	global $feedback;
	if (logged_in_as('super admin'))
	{
		foreach($group_ids as $group_id)
		{
			$sql = "	INSERT INTO user_group(user_id, group_id) 
						VALUES($user_id, $group_id)";
			
			$r = db_query($sql);
			if(db_error())
			{
				$feedback .= db_error()."<br>";
				$feedback .= $sql;
				$failed = true;
			}
		}
		if(isset($failed) && !$failed)
		{
			$feedback .= "User added to group.<br>";
		}
	}else{
		$feedback .= "You do not have enough permission to add users to groups.<br>";
	}
}

function add_contacts($user_id, $contact_list_ids)
{
	global $feedback;
	if (logged_in_as('super admin'))
	{
		foreach($contact_list_ids as $contact_list_id)
		{
			$sql = "	INSERT INTO user_contact_list(user_id, contact_list_id)
						VALUES($user_id, $contact_list_id)";
			
			$r = db_query($sql);
			if(db_error())
        	{
				$feedback .= db_error()."<br>";
				$feedback .= $sql;
				$failed = true;
			}
		}
		if(isset($failed) && !$failed)
		{
			$feedback .=  "User added to contact list.<br>";
		}
	}else{
		$feedback .= "You do not have enough permission to add users to contact lists.<br>";
	}
}

function remove_groups($user_id, $group_ids)
{
	global $feedback;
	if (logged_in_as('super admin'))
	{
		foreach($group_ids as $group_id)
		{
			$sql = "	DELETE FROM user_group
						WHERE user_id = $user_id
						AND group_id=$group_id";
			
        	$r = db_query($sql);
        	if(db_error())
        	{
				$feedback .= db_error()."<br>";
				
				$failed = true;
			}
		}
		if(isset($failed) && !$failed)
		{
			$feedback .= "User removed from group.<br>";
		}
	}else{
		$feedback .= "You do not have enough permission to remove a user from a group.<br>";
	}
}

function remove_contacts($user_id, $contact_list_ids)
{
	global $feedback;
	if (logged_in_as('super admin'))
	{
		foreach($contact_list_ids as $contact_list_id)
		{
			$sql = "	DELETE FROM user_contact_list
							WHERE user_id = $user_id
							AND contact_list_id = $contact_list_id";
        	$r = db_query($sql);
			if(db_error())
        	{
				$feedback .= db_error()."<br>";
				$failed = true;
			}
		}
		if(isset($failed) && !$failed)
		{
			$feedback .= "User removed from contact list.<br>";
		}
	}else{
		$feedback .= "You do not have enough permission to remove a user from a contact list.<br>";
	}
}

function add_regions($user_id, $region_list_ids){
	global $feedback;
	
	if (logged_in_as('super admin')){
		$t = new DB_Table('user_region_list');
		foreach($region_list_ids as $region_list_id){
			if(!$t->insert(Array('user_id'=>$user_id, 'region_list_id'=>$region_list_id))){
				$feedback .= $t->error_str."<br>";
				$failed = true;
			}
		}
		if(isset($failed) && !$failed){
			$feedback .=  "User added to region list.<br>";
		}
	}else{
		$feedback .= "You do not have enough permission to add users to region lists.<br>";
	}
}
function remove_regions($user_id, $region_list_ids){
	global $feedback;
	
	if (logged_in_as('super admin')){
		$t = new DB_Table('user_region_list');
		foreach($region_list_ids as $region_list_id){
			if(!$t->delete(Array('user_id'=>$user_id, 'region_list_id'=>$region_list_id))){
				$feedback .= $t->error_str()."<br>";
				$failed = true;
			}
		}
		if(isset($failed) && !$failed){
			$feedback .= "User removed from region list.<br/>";
		}
	}else{
		$feedback .= "You do not have enough permission to remove a user from a region list.<br/>";
	}
}
function new_user()
{
	return $this->edit_user();
}

function get_user($user_id)
{
	$sql = "	SELECT *
				FROM users
				WHERE user_id = $user_id";
	//echo $sql;
	$result = DB::query($sql);
	if(DB::error()){
		global $feedback;
		$feedback .=  DB::error()."<br>";
		$feedback .=  $sql;
		
	}
	//Get the results
	if (DB::numrows($result) == 0){
		$feedback .= "Could not find id $user_id in the database.<br>";
	}else{ //There is an id to edit
		return DB::fetch_assoc($result);
	}
}

function edit_user($user=null){
	//require_once('Template.php');
	global $feedback;
	$GLOBALS['page_title'] = 'Edit User';
	$t = new Template();
	$t->assign('admin', logged_in_as('super admin'));
	if (get_user_id() == safe_get($_GET['user_id']) || logged_in_as('super admin')){
		if(isset($user)){
			if(isset($user['user_id'])){
				$t->assign('group_form', $this->group_form($user['user_id']));
				$t->assign('contact_form', $this->contact_form($user['user_id']));
				$t->assign('region_form', $this->region_form($user['user_id']));
			}else{
				echo "No user id";
			}
			$t->assign('user', $user);
		}else{
			//echo "No user";
		}
		$content = $t->fetch(App::getTempDir().'user_edit.tpl');
	}else{
		$content = "You do not have enough permission to view this user.";
	}//end if
	return $content;
}

function region_form($user_id){
	global $feedback;
	if (logged_in_as('super admin')){		
		//Regions sub-form
		
		$t = new Template();
		$t->assign('user_id', $user_id);
		$sql = "	SELECT *
					FROM region_lists
					WHERE id NOT IN (SELECT url.region_list_id
									FROM user_region_list url
									WHERE url.user_id = $user_id)";
		
		$re = DB::query($sql);
		if(DB::error()){
			$feedback .= DB::error()."<br>";
			$feedback .= $sql;
		}
		$unlinked_options = Array();
		while ($row = DB::fetch_assoc($re)){
			$unlinked_options[$row['id']] = $row['name'];
		}
		$t->assign('unlinked_options', $unlinked_options);
		$sql = "	SELECT *
					FROM region_lists
					WHERE id IN (	SELECT url.region_list_id
									FROM user_region_list url
									WHERE url.user_id = $user_id)";
		
		$re = DB::query($sql);
		if(DB::error()){
			$feedback .= DB::error();
			$feedback .= $sql;
		}
		$linked_options = Array();
		while ($row = DB::fetch_assoc($re)){
			$linked_options[$row['id']] = $row['name'];
		}
		$t->assign('linked_options', $linked_options);
		
		return $t->fetch(App::getTempDir().'region_form.tpl');
	}/*else{
		echo "Not super admin";
	}*/
}

function contact_form($user_id){
	global $feedback;
	if (logged_in_as('super admin')){
		$t = new Template();
		$t->assign('user_id', $user_id);
		$sql = "	SELECT *
					FROM contact_lists
					WHERE contact_list_id NOT IN (	SELECT ucl.contact_list_id
													FROM user_contact_list ucl
													WHERE ucl.user_id = $user_id)";
		
		$re = DB::query($sql);
		if(DB::error()){
			$feedback .= DB::error()."<br>";
			$feedback .= $sql;
		}
		$unlinked_options = Array();
		while ($row = DB::fetch_assoc($re)){
			$unlinked_options[$row['contact_list_id']] = $row['contact_list_name'];
		}
		$t->assign('unlinked_options', $unlinked_options);
		$sql = "	SELECT *
					FROM contact_lists
					WHERE contact_list_id IN(	SELECT ucl.contact_list_id
												FROM user_contact_list ucl
												WHERE ucl.user_id = $user_id)";
		//echo $sql;
		$re = DB::query($sql);
		if(DB::error()){
			$feedback .= DB::error();
			$feedback .= $sql;
		}
		$linked_options = Array();
		while ($row = DB::fetch_assoc($re)){
			$linked_options[$row['contact_list_id']] = $row['contact_list_name'];
		}
		$t->assign('linked_options', $linked_options);
		return $t->fetch(App::getTempDir().'contact_form.tpl');	
	}
	
}


function group_form($user_id){
	global $feedback;
	$content ='';
	if (logged_in_as('super admin')){
		$t = new Template();
		$t->assign('user_id', $user_id);
		//Groups sub-form
		$sql = "	SELECT *
					FROM groups
					WHERE group_id NOT IN (	SELECT group_id 
											FROM user_group ug 
											WHERE ug.user_id = $user_id)";
				
		$re = DB::query($sql);
		if(DB::error()){
			$feedback .= DB::error()."<br/>";
			$feedback .= $sql;
		}
		$unlinked_options = Array();
		while ($row = DB::fetch_assoc($re)){
			$unlinked_options[$row['group_id']] = $row['group_name'];
		}
		$t->assign('unlinked_options', $unlinked_options);				
		$sql = "	SELECT *
					FROM groups
					WHERE group_id IN(	SELECT ug.group_id
										FROM user_group ug
										WHERE ug.user_id = $user_id)";
		$re = DB::query($sql);
		if(DB::error()){
			$feedback .= DB::error()."<br/>";
			$feedback .= $sql;
		}
		$linked_options = Array();
		while ($row = DB::fetch_assoc($re)){
		    $linked_options[$row['group_id']] = $row['group_name'];
		}
		$t->assign('linked_options', $linked_options);
		return $t->fetch(App::getTempDir().'group_form.tpl');	
	}//end if (isAdmin($username))
}

function show_list(){
	global $feedback;
	$t = new Template();
	$super_admin = logged_in_as('super admin');
	$t->assign('super_admin', $super_admin);
	$sql = "SELECT	u.*,
							IF(u.active, 'Yes', 'No') active,
							(	SELECT GROUP_CONCAT(g.group_name SEPARATOR ', ')
								FROM groups g, user_group ug
								WHERE g.group_id = ug.group_id
								AND ug.user_id = u.user_id
								GROUP BY u.user_id) groups,
							(	SELECT GROUP_CONCAT(cl.contact_list_name SEPARATOR ', ')
								FROM contact_lists cl, user_contact_list ucl
								WHERE cl.contact_list_id = ucl.contact_list_id
								AND ucl.user_id = u.user_id
								GROUP BY u.user_id) lists,
							(	SELECT GROUP_CONCAT(rl.name SEPARATOR ', ')
								FROM region_lists rl, user_region_list url
								WHERE rl.id = url.region_list_id
								AND url.user_id = u.user_id
								GROUP BY u.user_id) regions
					FROM users u ";
	if(isset($_GET['filter'])){
		$filter = $_GET['filter'];
		$filter_str = "&filter=$_GET[filter]";
		if ($filter == "inactive")
		{
			$sql .= "where active = 0 ";
			
		}elseif($filter == "active"){
			$sql .= "where active = 1 ";
			
		}elseif($filter == "all"){
		//
			
		}
	}else{
		$sql .= "where active = 1 ";
		$filter_str = '';
	}
	
	if (isset($_GET['sort'])){
		if (isset($_GET['dir'])){
			$dir = $_GET['dir'];
		}else{
			$dir = "asc";
		}
		
		$sort = $_GET['sort'];
		switch($sort){
		case "date_created":
            $sql .= "ORDER BY date_created $dir";  
            break;
		case "last_updated":
			$sql .= "ORDER BY last_updated $dir";
			break;
		case "email":		
			$sql .= "ORDER BY email $dir";
			break;
		case "username":
			$sql .= "ORDER BY username $dir";
			break;
		case "first_name":
			$sql .= "ORDER BY first_name $dir";
			break;
		case "last_name":
			$sql .= "ORDER BY last_name $dir";
			break;
		case "groups":
			$sql .= "ORDER BY groups $dir";
			break;
		case "lists":
			$sql .= "ORDER BY lists $dir";
			break;
		case "regions":
			$sql .= "ORDER BY regions $dir";
			break;
		case "active":
			$sql .= "ORDER BY active $dir";
			break;
		default:
			$sql .= "ORDER BY user_id $dir";
			break;
		}
	}else{
		$sql .= " order by user_id";
	}
	
	if(isset($dir) && $dir == "asc"){
		$oppDir = "desc";
	}else{
		$oppDir = "asc";
	}
	$t->assign('oppDir', $oppDir);
	$re = DB::query($sql);
	if (DB::error()){
		$feedback .= DB::error();
		$feedback .= "$sql<br>";
	}
	$t->assign('users', DB::to_array($re));
	return $t->fetch(App::getTempDir().'user_list.tpl');
}

function get_customers()
{
	global $feedback;
	$sql = "SELECT customer_id, name customer_name, city, state FROM customer WHERE acct_owner = ". get_user_ID();
	
	$p = new portal($sql);
	$p->set_row_action("\"refresh_cust_notes('\$id')\";");
	$p->set_primary_key('customer_id');
	$p->hide_column('customer_id');
	$p->set_table('customer');
	//$c = $p->render();
	$c = "<table border=0 width='100%'><tr><th>Customers</th><th>Notes</th></tr><tr><td width='50%'>".$p->render()."</td><td width='50%' id='cust_owner_notes_portal'></td></tr></table>";
	$c .= $this->portal_script();
	$c .= "
	<script type=\"text/javascript\">
	function refresh_cust_notes(cust_id){
		get_portal('cust_owner_notes', 'customer_id='+cust_id);
	}
	function new_note(cust_id){
		//alert(cust_id);
	}
	</script>";
	return $c;
}

function get_cust_notes($cust_id)
{
	global $feedback;
	$sql = "SELECT cust_note_id, note, note_date
			FROM cust_owner_notes
			WHERE customer_id = $cust_id
			AND user_id = ". get_user_ID();
	//require_once"includes/portal.php";
	$p = new portal($sql);
	
	$p->set_primary_key('cust_note_id');
	$p->hide_column('cust_note_id');
	$p->set_table('cust_owner_notes');
	$c = "
	<script type='text/javascript'>
		
	</script>";
	$c .= "<input type='button' value='New Note' onclick='new_note($cust_id)'>";
	
	$c .= $p->render();
	return $c;
}
}
$u = new users();
$content = $u->process_users();
global $feedback;
echo $feedback;
echo $content;
?>
