<?php

ini_set('display_errors', 'On');
require_once "includes/global.php";
require_once "includes/auth.php";
require_once "includes/html.php";
require_once "includes/dts_table.php";
$post_action = '';


//print_r($_REQUEST);

class users extends dts_table
{
function users()
{
	$this->dts_table('users');
}
function process_users()
{
	global $feedback;
	$content = '';
	switch (get_action())
	{
		case "removegroups":
			$this->remove_groups($_REQUEST['user_id'], $_POST['linkedgroups']);
			$content .= $this->edit_user($this->get_user($_REQUEST['user_id']));
			break;
		case "addcontacts":
			$this->add_contacts($_REQUEST['user_id'], $_POST['unlinkedcontacts']);
			$content .= $this->edit_user($this->get_user($_REQUEST['user_id']));
			break;
		case "removecontacts":
			$this->remove_contacts($_REQUEST['user_id'], $_POST['linkedcontacts']);
			$content .= $this->edit_user($this->get_user($_REQUEST['user_id']));
			break;
		case "addgroups":
			$this->add_groups($_REQUEST['user_id'], $_POST['unlinkedgroups']);
			$content .= $this->edit_user($this->get_user($_REQUEST['user_id']));
			break;
		case "Save":
			$_POST['active'] = checkbox_to_binary($_POST['active']);
			$this->save_user();
			$content .= $this->show_list();
			break;
		case "Cancel":
			$feedback .= "User cancelled.";
			$content .= $this->show_list();
			break;
		case "edit":
			$content .= $this->edit_user($this->get_user($_REQUEST['user_id']));
			break;
		case "new":
			$content .= $this->new_user();
			break;
		case "cust":
			$content .= $this->get_customers();
			break;
		case "portal":
			if($_REQUEST['portal'] == 'cust_owner_notes')
			{
				$content .= $this->get_cust_notes($_REQUEST['customer_id']);
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

function save_user()
{
	global $feedback;
	//*****FIX V****

	if (logged_in_as('super admin') || logged_in_as($_COOKIE[COOKIE_USERNAME]))
	{
		//print_r($_REQUEST['user_id']);
		if(isset($_REQUEST['user_id']))
		{
			$sql = "	UPDATE users SET 
						username = '$_POST[username]',
						first_name = '$_POST[first_name]',
						last_name = '$_POST[last_name]',
						email = '$_POST[email]', ";
			if($_POST['password1'] && $_POST['password2'])
			{
				if(($_POST['password1'] == $_POST['password2']))
				{
					$sql .= "password = '$_POST[password1]',";
				}else
				{
					$feedback .= "Passwords do not match";
				}
			}
			$sql .= "	active = $_POST[active],
						last_updated = NOW()
						WHERE user_id = $_POST[user_id]";
		}else{
			$sql = "INSERT INTO users(username,first_name, last_name, email, password, active, last_updated)
								VALUES(	'".safe_get($_REQUEST['username'])."',
										'".safe_get($_REQUEST['first_name'])."',
										'".safe_get($_REQUEST['last_name'])."',
										'".safe_get($_REQUEST['email'])."',";
			if($_POST['password1'] && $_POST['password2'])
			{
				if(($_POST['password1'] == $_POST['password2']))
				{
					$sql .= "			'".safe_get($_POST['password1'])."',
										".safe_get($_POST['active']).",
										NOW())";
				}else
				{
					$feedback .= "Passwords do not match";
				}
			}
		}
			$r = db_query($sql);
			if(db_error())
		{
			$feedback .= db_error()."<br>";
			$feedback .= $sql;
		}else{
			$feedback .= "User Saved.<br>";
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
function new_user()
{
	return $this->edit_user();
}

function get_user($user_id)
{
	$sql = "	SELECT *
				FROM users
				WHERE user_id = $user_id";

	$result = db_query($sql);
	if(db_error())
	{
		global $feedback;
		$feedback .=  db_error()."<br>";
		$feedback .=  $sql;
		
	}
	//Get the results
	if (db_numrows($result) == 0)
	{
		$feedback .= "Could not find id $user_id in the database.<br>";
	}else{ //There is an id to edit
		return db_fetch_assoc($result);
	}
}

function edit_user($user=null)
{//Fetch the user to edit
	global $feedback;
	if (get_user_id() == safe_get($_REQUEST['user_id']) || logged_in_as('super admin'))
	{
		$active = $user['active'];
		if ($active == 1){
			$active = "checked";
		}
		else{
			$active = "";
		}
		$content = "
		<table class='simpleframe'>
			<tr>
			<form method='POST' action=''>
				<input type='hidden' name='page' value='users'>";
		if(isset($user['user_id']))
		{
			$content .= "	<input type='hidden' name='user_id' value='".safe_get($user['user_id'])."'>";
		}
		$content .= "
				<input type='hidden' name='referer' value='$_SERVER[HTTP_REFERER]'>
				<input type='hidden' name='action' value='save'>
				<td colspan=2 class='title'>
					<center>Edit User</center>
				</td>
			</tr>";
			
		//Fields
		$content .= "<tr><td class='label'>First Name:</td><td class='editstyle'><INPUT TYPE='text' name='first_name' VALUE='".safe_get($user['first_name'])."'></td></tr>\n";
		$content .= "<tr><td class='label'>Last Name:</td><td class='editstyle'><INPUT TYPE='text' name='last_name' VALUE='".safe_get($user['last_name'])."'></td></tr>\n";
		$content .= "<tr><td class='label'>Email Address:</td><td class='editstyle'><INPUT TYPE='text' name='email' VALUE='".safe_get($user['email'])."'></td></tr>\n";
		$content .= "<tr><td class='label'>Username:</td><td class='editstyle'><INPUT TYPE='text' name='username' VALUE='".safe_get($user['username'])."'></td></tr>\n";
		$content .= "<tr><td class='label'>Password:</td><td class='editstyle'><INPUT TYPE='password' name='password1'></td></tr>\n";
		$content .= "<tr><td class='label'>Confirm Password:</td><td class='editstyle'><INPUT TYPE='password' name='password2'></td></tr>\n";
		$content .= "<tr><td class='label'>Active:</td><td class='editstyle'><INPUT TYPE='checkbox' name='active' $active></td></tr>\n";
			
		//Buttons
		$content .= "
					<tr>
						<td>
							<INPUT TYPE='Submit' name='action' VALUE='Save'>
						</td>
						<td>
							<INPUT TYPE='submit' name='action' VALUE='Cancel'>
						</td>
					</form>
					</tr>\n";
		$content .= "</tr>\n";
		if(isset($_REQUEST['user_id']))
		{
			$content .= $this->group_form($_REQUEST['user_id']);
			$content .= $this->contact_form($_REQUEST['user_id']);
		}
	}else{
		$content = "You do not have enough permission to view this user.";
	}//end if
	return $content;
}//end showUser

/*function htpasswd($pass)
{ 
     $pass = crypt(trim($pass),base64_encode(CRYPT_STD_DES)); 
     return $pass; 
}*/


function contact_form($user_id)
{
	global $feedback;
	if (logged_in_as('super admin'))
	{		
		//Groups sub-form
		$content = "
					<table border=0 class='simpleframe'>
						<tr>
							<td colspan=3 class='subhead'>
								<center>Contact Lists</center>
							</th>
						</tr>
								<tr>
									<form method='post' action=''>
										<input type='hidden' name='page' value='users'>
										<input type='hidden' name='action' value='addcontacts'>
										<input type='hidden' name='user_id' value='$user_id'>
									<td style='text-align:right;width:33%'>
										Available Lists:<br>
										";
				
		$sql = "	SELECT *
					FROM contact_lists
					WHERE contact_list_id NOT IN(SELECT ucl.contact_list_id FROM user_contact_list ucl WHERE ucl.user_id=$user_id)";
		$content .= "
											<select name='unlinkedcontacts[]' multiple style='width:15em;height:5em'>";
		$result = db_query($sql);
		if(db_error())
		{
			$feedback .= db_error()."<br>";
			$feedback .= $sql;
		}
		while ($row = db_fetch_assoc($result))
		{
			$content .= "
											<option title='$row[contact_list_name]' value='$row[contact_list_id]'>$row[contact_list_name]</option>";
		}
		$content .= "
											</select>";
		//echo "Unlinked Query: " . $sql . "<br>\n";//debug
				
		$content .= "
									</td>
									<td width=1% valign=middle>
										<center>
										<INPUT TYPE='Submit' VALUE='Add'><br>
										</form>
										<form method='post' action=''>
											<input type='hidden' name='page' value='users'>
											<input type='hidden' name='action' value='removecontacts'>
											<input type='hidden' name='user_id' value='$user_id'>
											<INPUT TYPE='Submit' VALUE='Remove'>
										</center>
									</td>
									<td style='text-align:left;width:33%'>
										Assigned Lists:<br>
										<select name='linkedcontacts[]' multiple style='width:15em;height:5em'>";
		$sql = "	SELECT *
					FROM contact_lists
					WHERE contact_list_id IN(	SELECT ucl.contact_list_id
												FROM user_contact_list ucl
												WHERE ucl.user_id=$user_id)";
		$result = db_query($sql);
		if(db_error())
		{
			$feedback .= db_error();
			$feedback .= $sql;
		}
		
				
		                while ($row = db_fetch_assoc($result))
		                {
		                        $content .= "
		                        			<option title='$row[contact_list_name]' value='$row[contact_list_id]'>$row[contact_list_name]</option>\n";
		                }       
				$content .= "
										</select>
									</form>
									</td>
								</tr>
							</table>";
			}
	return $content;
}

function group_form($user_id)
{
	global $feedback;
	$content ='';
	if (logged_in_as('super admin'))
			{		
				
				//Groups sub-form
				$content .= "
							<table class='group'>
							<tr>
								<td colspan=3 class='subhead'>
									<center>Groups</center>
								</td>
							</tr>
							<tr>
								<form method='post' action=''>
									<input type='hidden' name='page' value='users'>
									<input type='hidden' name='action' value='addgroups'>
									<input type='hidden' name='user_id' value='$user_id'>
								<td style='text-align:right;width:33%'>
									Available Groups:<br>
										";
				
				$sql = "	SELECT group_id, group_name
							FROM groups
							WHERE group_id NOT IN(select group_id from user_group ug where ug.user_id=$user_id)";
				$content .= "
											<select name='unlinkedgroups[]' multiple style='width:15em;height:5em'>";
				$ul_group_result = db_query($sql);
				if(db_error())
				{
					$feedback .= db_error()."<br>";
					$feedback .= $sql;
				}
				while ($ulg_row = db_fetch_assoc($ul_group_result))
				{
					$ulg_group_name = $ulg_row['group_name'];
					$ulg_group_id = $ulg_row['group_id'];
					$content .= "
												<option title='$ulg_group_name' value='$ulg_group_id'>$ulg_group_name</option>";
				}
				$content .= "
											</select>";
				//echo "Unlinked Query: " . $sql . "<br>\n";//debug
				
				$content .= "
									</td>
									<td width=1%>
										<center>
										<INPUT TYPE='Submit' VALUE='Add'><br>
										</form>
										<form method='post' action=''>
											<input type='hidden' name='page' value='users'>
											<input type='hidden' name='action' value='removegroups'>
											<input type='hidden' name='user_id' value='$user_id'>
											<INPUT TYPE='Submit' VALUE='Remove'>
										</center>
									</td>
									<td style='text-align:left;width:33%'>
										Assigned Groups:<br>
										<select name='linkedgroups[]' multiple style='width:15em;height:5em'>";
								
				$sql = "	SELECT *
								FROM groups
								WHERE group_id IN(	SELECT ug.group_id
															FROM user_group ug
															WHERE ug.user_id = $user_id)";
		                $l_group_result = db_query($sql);
		                if(db_error())
		                {
			                $feedback .= db_error()."<br>";
		                	$feedback .= $sql;
		                }
		                //$debug .= "Linked Query: " . $sql . "<br>\n";
				
		                while ($lg_row = db_fetch_assoc($l_group_result))
		                {
		                        $ul_group_name = $lg_row['group_name'];
		                        $ul_group_id = $lg_row['group_id'];
		                        $content .= "
		                        			<option title='$ul_group_name' value='$ul_group_id'>$ul_group_name</option>\n";
		                }       
				$content .= "
										</select>
									</form>
									</td>
								</tr>
							</table>";
			}//end if (isAdmin($username))
		return $content;
}

function show_list()
{
	global $feedback;
	
	$is_admin = logged_in_as('super admin');
	
	$content = "
	<a href='http://".HTTP_ROOT."/?page=users&action=new'><div class='menu'>New User</div></a>
	<table class='center'>
		<tr>
			<td colspan=8>
				<center><b><u>" . SITE_NAME . " User List</b></u></center>
			</td>
		</tr>";
	$content .= "</tr>";
	$font_size = -1;
	$sql = "SELECT	u.*,
							IF(u.active, 'Yes', 'No') active,
							(	SELECT GROUP_CONCAT(g.group_name SEPARATOR '<br>')
								FROM groups g, user_group ug
								WHERE g.group_id = ug.group_id
								AND ug.user_id = u.user_id
								GROUP BY u.user_id) groups,
							(	SELECT GROUP_CONCAT(cl.contact_list_name SEPARATOR '<br>')
								FROM contact_lists cl, user_contact_list ucl
								WHERE cl.contact_list_id = ucl.contact_list_id
								AND ucl.user_id = u.user_id
								GROUP BY u.user_id) lists
					FROM users u ";
	if(isset($_GET['filter']))
	{
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
	
	if (isset($_GET['sort']))
	{
		if (isset($_GET['dir']))
		{
			$dir = $_GET['dir'];
		}else{
			$dir = "asc";
		}
		
		$sort = $_GET['sort'];
		switch($sort){
		case "date_created":
                        $sql .= "order by date_created $dir";  
                        break;
		case "last_updated":
			$sql .= "order by last_updated $dir";
			break;
		case "email":		
			$sql .= "order by email $dir";
			break;
		case "username":
			$sql .= "order by username $dir";
			break;
		case "first_name":
			$sql .= "order by first_name $dir";
			break;
		case "last_name":
			$sql .= "order by last_name $dir";
			break;
		case "groups":
			$sql .= "order by groups $dir";
			break;
		case "lists":
			$sql .= "order by lists $dir";
			break;
		case "active":
			$sql .= "order by active $dir";
			break;
		default:
			$sql .= "order by user_id $dir";
			break;
		}
	}else{
		$sql .= " order by user_id";
	}
	//echo $sql;
	
	
	if (isset($dir) && $dir == "asc")
	{
		$oppDir = "desc";
	}else{
		$oppDir = "asc";
	}
		
	$result = db_query($sql);
	if (db_error())
	{
		$feedback .= db_error();
		$feedback .= "<br>$sql";
	}
	$content .= "<tr><td colspan=11>";
	$content .= "
			<table border=0>
				<tr>
					<td class='smalltext'>
						".db_numrows($result)." users
					</td>
					<td class='smalltext'>
						Filter:
					</td>
					<td class='smalltext'>
						<a href='http://".HTTP_ROOT."/?page=users&filter=inactive'>Inactive</a>
					</td>
					<td class='smalltext'>
						<a href='http://".HTTP_ROOT."/?page=users&filter=active'>Active</a>
					</td>
					<td class='smalltext'>
						<a href='http://".HTTP_ROOT."/?page=users&filter=all'>All</a>
					</td>
				</tr>
			</table>
			";
	$content .= "</td></tr><tr>";
	$content .= "<td class='smalltext'><center><a href='http://".HTTP_ROOT."/?page=users&sort=user_id&dir=$oppDir$filter_str'>User ID</a></center></td>";
	$content .= "<td class='smalltext'><center><a href='http://".HTTP_ROOT."/?page=users&sort=username&dir=$oppDir$filter_str'>Username</a></center></td>";
	$content .= "<td class='smalltext'><center><a href='http://".HTTP_ROOT."/?page=users&sort=first_name&dir=$oppDir$filter_str'>First<a/></center></td>";
	$content .= "<td class='smalltext'><center><a href='http://".HTTP_ROOT."/?page=users&sort=last_name&dir=$oppDir$filter_str'>Last<a/></center></td>";
	$content .= "<td class='smalltext'><center><a href='http://".HTTP_ROOT."/?page=users&sort=email&dir=$oppDir$filter_str'>Email Address</a></center></td>";
	$content .= "<td class='smalltext'><center><a href='http://".HTTP_ROOT."/?page=users&sort=last_updated&dir=$oppDir$filter_str'>Last Updated</a></center></td>";
	$content .= "<td class='smalltext'><center><a href='http://".HTTP_ROOT."/?page=users&sort=date_added&dir=$oppDir$filter_str'>Date Added</a></center></td>";
	$content .= "<td class='smalltext'><center><a href='http://".HTTP_ROOT."/?page=users&sort=active&dir=$oppDir$filter_str'>Active</a></center></td>";
	$content .= "<td class='smalltext'><center><a href='http://".HTTP_ROOT."/?page=users&sort=groups&dir=$oppDir$filter_str'>Groups</a></center></td>";
	$content .= "<td class='smalltext'><center><a href='http://".HTTP_ROOT."/?page=users&sort=lists&dir=$oppDir$filter_str'>Contact Lists</a></center></td>";
	$content .= "<td class='smalltext'><center>Edit</center></td>";
		
	
	$content .= "</tr>";
	$i=0;
	while ($row = db_fetch_assoc($result)) {
		
		if ($i % 2 == 0){
			$content .= "<tr class='label'>\n";
		}else{
			$content .= "<tr class='label' bgcolor='".safe_get($alt_color)."'>\n";
		} 	
		$content .= "<td class='smalltext'>$row[user_id]</td>\n";
		$content .= "<td class='smalltext'>$row[username]</td>\n";
		$content .= "<td class='smalltext'>$row[first_name]</td>\n";
		$content .= "<td class='smalltext'>$row[last_name]</td>\n";
		$content .= "<td class='smalltext'>$row[email]</td>\n";
		$content .= "<td class='smalltext'>$row[last_updated]</td>\n";
		$content .= "<td class='smalltext'>$row[date_created]</td>\n";
		$content .= "<td class='smalltext'><center>$row[active]</center></td>\n";
		$content .= "<td class='smalltext'><center>$row[groups]</center></td>\n";
		$content .= "<td class='smalltext'><center>$row[lists]</center></td>\n";
		
		if(logged_in_as('super admin') || $row['user_id'] == get_user_ID())
		{
			$content .= "
					<form method=get action=''>
						<input type='hidden' name='page' value='users'>
					<td>
						<input type=hidden name='action' value='edit'>
						<input type=hidden name='user_id' value='$row[user_id]'>
						<input type=submit value='Edit'>
					</td>
					</form>";
		}else{
			$content .= "<td>No Access</td>";
		}
		$content .= "</tr>";
		$i++;
	}
	return $content;
}

function get_customers()
{
	global $feedback;
	$sql = "SELECT customer_id, name customer_name, city, state FROM customer WHERE acct_owner = ". get_user_ID();
	require_once"includes/portal.php";
	$p = new portal($sql);
	$p->set_row_action("\"refresh_cust_notes('\$id')\";");
	$p->set_primary_key('customer_id');
	$p->hide_column('customer_id');
	$p->set_table('customer');
	//$c = $p->render();
	$c = "<table border=0 width='100%'><tr><th>Customers</th><th>Notes</th></tr><tr><td width='50%'>".$p->render()."</td><td width='50%' id='cust_owner_notes_portal'></td></tr></table>";
	$c .= $this->portal_script();
	$c .= "
	<script>
	function refresh_cust_notes(cust_id)
	{
		get_portal('cust_owner_notes', 'customer_id='+cust_id);
	}
	function new_note(cust_id)
	{
		alert(cust_id);
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
	require_once"includes/portal.php";
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
