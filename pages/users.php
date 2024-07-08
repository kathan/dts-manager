<?php
ini_set('display_errors', 'On');//Debug only

require_once("includes/global.php");
require_once("includes/auth.php");
require_once("includes/html.php");
require_once("includes/dts_table.php");
require_once("includes/Template.php");
require_once("includes/feedback.php");
require_once("includes/DbTable.php");
require_once("includes/portal.php");
$post_action = '';
$GLOBALS["page_title"] = "Users";

class users extends dts_table{
	private $userTable;
	private $userGroupTable;
	private $contactListTable;
	private $regionListTable;

	function __construct(){
		parent::__construct('users');
		$this->userTable = new DbTable(App::$db, 'users');
		$this->userGroupTable = new DbTable(App::$db, 'user_group');
		$this->contactListTable = new DbTable(App::$db, 'user_contact_list');
		$this->regionListTable = new DbTable(App::$db, 'user_region_list');
	}

	function process_users(){
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
		if(isset($_GET['user_id'])){
			$user_id = $_GET['user_id'];
		}
		isset($_GET['action']) ? $action = $_GET['action'] : $action = '';
		switch ($action){
			case "Cancel":
				Feedback::add("User cancelled.");
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
		
	function check_user_form(){
	}

	function save_user(){
		if (Auth::loggedInAs('super admin') || Auth::loggedInAs($_COOKIE[Auth::COOKIE_USERNAME])){
			if(isset($_REQUEST['user_id']) && $_REQUEST['user_id'] !== ''){
				$data = $_POST;
				if(isset($_POST['password1']) && isset($_POST['password2']) && $_POST['password1'] !== '' && $_POST['password2'] !== ''){
					if($_POST['password1'] === $_POST['password2'] ){
						$data['hash_password'] = Auth::encryptPassword($_POST['password1']);
					}else{
						Feedback::add("Passwords do not match.");
					}
				}
				unset($data['password1']);
				unset($data['password2']);
				if($this->userTable->update($data, ['user_id'=>$_GET['user_id']])){
					Feedback::add("User Updated");
				}else{
					Feedback::add($this->error_str);
				}
			}else{
				if($_POST['password1'] && $_POST['password2']){
					if(($_POST['password1'] === $_POST['password2'])){
						$data = $_POST;
						
						$data['hash_password'] = Auth::encryptPassword($_POST['password1']);
						unset($data['password1']);
						unset($data['password2']);
						if($this->userTable->insert($data)){
							Feedback::add("User Added");
							header('Location: ?page='.basename(__FILE__, '.php').'&action=edit&user_id='.$this->userTable->last_id);
						}else{
							Feedback::add($this->userTable->error_ary);
						}
					}else{
						Feedback::add("Passwords do not match.");
					}
				}else{
					Feedback::add("Password must be set.");
				}
			}
		}else{
			noAuth();
		}
	}

	function add_groups($user_id, $group_ids){
		if (Auth::loggedInAs('super admin')){
			foreach($group_ids as $group_id){
				$result = $this->userGroupTable->insert(['user_id'=>$user_id, 'group_id' => $group_id]);
				if(!$result){
					$failed = true;
				}
			}
			if(isset($failed) && !$failed){
				Feedback::add("User added to group.");
			}
		}else{
			Feedback::add("You do not have enough permission to add users to groups.");
		}
	}

	function add_contacts($user_id, $contact_list_ids){
		if (Auth::loggedInAs('super admin')){
			foreach($contact_list_ids as $contact_list_id){
				$result = $this->contactListTable->insert(['user_id'=>$user_id, 'contact_list_id' => $contact_list_id]);
				if(!$result){
					$failed = true;
				}
			}
			if(isset($failed) && !$failed){
				Feedback::add("User added to contact list.");
			}
		}else{
			Feedback::add("You do not have enough permission to add users to contact lists.");
		}
	}

	function remove_groups($user_id, $group_ids){
		if (Auth::loggedInAs('super admin')){
			foreach($group_ids as $group_id){
				$result = $this->userGroupTable->delete(['user_id' => $user_id, 'group_id' => $group_id]);
				if(!$result){
					$failed = true;
				}
			}
			if(isset($failed) && !$failed){
				Feedback::add("User removed from group.");
			}
		}else{
			Feedback::add("You do not have enough permission to remove a user from a group.");
		}
	}

	function remove_contacts($user_id, $contact_list_ids){
		if (Auth::loggedInAs('super admin')){
			foreach($contact_list_ids as $contact_list_id){
				$result = $this->contactListTable->delete(['user_id' => $user_id, 'contact_list_id' => $contact_list_id]);
				if(!$result){
					$failed = true;
				}
			}
			if(isset($failed) && !$failed){
				Feedback::add("User removed from contact list.");
			}
		}else{
			Feedback::add("You do not have enough permission to remove a user from a contact list.");
		}
	}

	function add_regions($user_id, $region_list_ids){
		if (Auth::loggedInAs('super admin')){
			foreach($region_list_ids as $region_list_id){
				if(!$this->regionListTable->insert(['user_id'=>$user_id, 'region_list_id'=>$region_list_id])){
					$failed = true;
				}
			}
			if(isset($failed) && !$failed){
				Feedback::add("User added to region list.");
			}
		}else{
			Feedback::add("You do not have enough permission to add users to region lists.");
		}
	}

	function remove_regions($user_id, $region_list_ids){
		if (Auth::loggedInAs('super admin')){
			foreach($region_list_ids as $region_list_id){
				if(!$this->regionListTable->delete(['user_id'=>$user_id, 'region_list_id'=>$region_list_id])){
					$failed = true;
				}
			}
			if(isset($failed) && !$failed){
				Feedback::add("User removed from region list.");
			}
		}else{
			Feedback::add("You do not have enough permission to remove a user from a region list.");
		}
	}

	function new_user(){
		return $this->edit_user();
	}

	function get_user($user_id){
		$sql = "	SELECT *
					FROM `users`
					WHERE user_id = ?";
		$binds = [$user_id];
		$stmt = App::$db->prepare($sql);
		$result = $stmt->execute($binds);
		if(!$result){
			return false;		
		}
			
		$r = $stmt->fetch(PDO::FETCH_ASSOC);
		if($r){
			return $r;
		}
		Feedback::add("Could not find id $user_id in the database.");
	}

	function edit_user($user=null){
		$GLOBALS['page_title'] = 'Edit User';
		$t = new Template();
		$t->assign('admin', Auth::loggedInAs('super admin'));
		if (Auth::getUserId() == safe_get($_GET['user_id']) || Auth::loggedInAs('super admin')){
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
			}
			$content = $t->fetch(App::getTempDir().'user_edit.tpl');
		}else{
			$content = "You do not have enough permission to view this user.";
		}//end if
		return $content;
	}

	function region_form($user_id){
		if (Auth::loggedInAs('super admin')){		
			//Regions sub-form
			
			$t = new Template();
			$t->assign('user_id', $user_id);
			$sql = "	SELECT *
						FROM region_lists
						WHERE id NOT IN (SELECT url.region_list_id
										FROM user_region_list url
										WHERE url.user_id = ?)";
			
			$binds = [$user_id];
			$stmt = App::$db->prepare($sql);
			$result = $stmt->execute($binds);
			if(!$result){
				return false;
			}
			$unlinked_options = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$unlinked_options[$row['id']] = $row['name'];
			}
			$t->assign('unlinked_options', $unlinked_options);
			$sql = "	SELECT *
						FROM region_lists
						WHERE id IN (	SELECT url.region_list_id
										FROM user_region_list url
										WHERE url.user_id = ?)";
			
			$binds = [$user_id];
			$stmt = App::$db->prepare($sql);
			$result = $stmt->execute($binds);
			if(!$result){
				return false;
			}
			$linked_options = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$linked_options[$row['id']] = $row['name'];
			}
			$t->assign('linked_options', $linked_options);
			
			return $t->fetch(App::getTempDir().'region_form.tpl');
		}
	}

	function contact_form($user_id){
		if (Auth::loggedInAs('super admin')){
			$t = new Template();
			$t->assign('user_id', $user_id);
			$sql = "	SELECT *
						FROM contact_lists
						WHERE contact_list_id NOT IN (	SELECT ucl.contact_list_id
														FROM user_contact_list ucl
														WHERE ucl.user_id = ?)";
			
			$binds = [$user_id];
			$stmt = App::$db->prepare($sql);
			$result = $stmt->execute($binds);
			if(!$result){
				return false;
			}
			$unlinked_options = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$unlinked_options[$row['contact_list_id']] = $row['contact_list_name'];
			}
			$t->assign('unlinked_options', $unlinked_options);
			$sql = "	SELECT *
						FROM contact_lists
						WHERE contact_list_id IN(	SELECT ucl.contact_list_id
													FROM user_contact_list ucl
													WHERE ucl.user_id = ?)";
			$binds = [$user_id];
			$stmt = App::$db->prepare($sql);
			$result = $stmt->execute($binds);
			if(!$result){
				return false;
			}
			$linked_options = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$linked_options[$row['contact_list_id']] = $row['contact_list_name'];
			}
			$t->assign('linked_options', $linked_options);
			return $t->fetch(App::getTempDir().'contact_form.tpl');	
		}
	}


	function group_form($user_id){
		$content ='';
		
		if (Auth::loggedInAs('super admin')){
			$t = new Template();
			$t->assign('user_id', $user_id);
			//Groups sub-form
			$sql = "	SELECT *
						FROM `groups`
						WHERE group_id NOT IN (	SELECT group_id 
												FROM user_group ug 
												WHERE ug.user_id = ?)";
			$binds = [$user_id];
			$stmt = App::$db->prepare($sql);
			$result = $stmt->execute($binds);
			if(!$result){
				
				return false;
			}
			$unlinked_options = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$unlinked_options[$row['group_id']] = $row['group_name'];
			}
			$t->assign('unlinked_options', $unlinked_options);				
			$sql = "	SELECT *
						FROM `groups`
						WHERE group_id IN(	SELECT ug.group_id
											FROM user_group ug
											WHERE ug.user_id = ?)";
			$binds = [$user_id];
			$stmt = App::$db->prepare($sql);
			$result = $stmt->execute($binds);
			if(!$result){
				return false;
			}
			$linked_options = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$linked_options[$row['group_id']] = $row['group_name'];
			}
			$t->assign('linked_options', $linked_options);
			return $t->fetch(App::getTempDir().'group_form.tpl');	
		}//end if (isAdmin($username))
	}

	function show_list(){
		$t = new Template();
		$super_admin = Auth::loggedInAs('super admin');
		$t->assign('super_admin', $super_admin);
		$sql = "SELECT	u.*,
								IF(u.active, 'Yes', 'No') active,
								(	SELECT GROUP_CONCAT(g.group_name SEPARATOR ', ')
									FROM `groups` g, `user_group` ug
									WHERE g.group_id = ug.group_id
									AND ug.user_id = u.user_id
									GROUP BY u.user_id) `groups`,
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
						FROM `users` u ";
		if(isset($_GET['filter'])){
			$filter = $_GET['filter'];
			$filter_str = "&filter=$_GET[filter]";
			if ($filter == "inactive"){
				$sql .= "where active = 0 ";
			}elseif($filter == "active"){
				$sql .= "where active = 1 ";
			}elseif($filter == "all"){
				
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
				$sql .= "ORDER BY `username` $dir";
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
				$sql .= "ORDER BY `active` $dir";
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
		$re = App::$db->query($sql);
		if (isset($re->errorCode) && $re->errorCode > 0){
			Feedback::add($re->errorCode());
		}
		$t->assign('users', $re->fetchAll(PDO::FETCH_ASSOC));
		return $t->fetch(App::getTempDir().'user_list.tpl');
	}

	function get_customers(){
		$sql = "SELECT customer_id, name customer_name, city, state 
				FROM customer
				WHERE acct_owner = ?";
		$binds = [Auth::getUserId()];
		$p = new portal($sql, $binds);
		$p->set_row_action("\"refresh_cust_notes('\$id')\";");
		$p->set_primary_key('customer_id');
		$p->hide_column('customer_id');
		$p->set_table('customer');
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

	function get_cust_notes($cust_id){
		$sql = "SELECT cust_note_id, note, note_date
				FROM cust_owner_notes
				WHERE customer_id = $cust_id
				AND user_id = ?";
		$binds = [Auth::getUserId()];
		$p = new portal($sql, $binds);
		
		$p->set_primary_key('cust_note_id');
		$p->hide_column('cust_note_id');
		$p->set_table('cust_owner_notes');
		$c = "<input type='button' value='New Note' onclick='new_note($cust_id)'>";
		$c .= $p->render();
		return $c;
	}
}
$u = new users();
$content = $u->process_users();
echo Feedback::get();
echo $content;
?>
