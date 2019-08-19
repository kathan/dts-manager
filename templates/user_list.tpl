<h3>DTS User List</h3>
<a href="?page=users&action=edit">New User</a>
{count($users)} users
Filter:
<a href="?page={$smarty.get.page}&filter=inactive">Inactive</a>
<a href="?page={$smarty.get.page}&filter=active">Active</a>
<a href="?page={$smarty.get.page}&filter=all">All</a>		
<table class="center list" cellpadding="0" cellspacing="0">
	<tr>
		<th class="smalltext">
			<a href="?page={$smarty.get.page}&sort=user_id&dir={$oppDir}{$filter_str}">User ID</a>
		</th>
		<th class="smalltext">
			<a href="?page={$smarty.get.page}&sort=username&dir={$oppDir}{$filter_str}">Username</a>
		</th>
		<th class="smalltext">
			<a href="?page={$smarty.get.page}&sort=first_name&dir={$oppDir}{$filter_str}">First</a>
		</th>
		<th class="smalltext">
			<a href="?page={$smarty.get.page}&sort=last_name&dir={$oppDir}{$filter_str}">Last</a>
		</th>
		<th class="smalltext">
			<a href="?page={$smarty.get.page}&sort=email&dir={$oppDir}{$filter_str}">Email Address</a>
		</th>
		<th class="smalltext">
			<a href="?page={$smarty.get.page}&sort=last_updated&dir={$oppDir}{$filter_str}">Last Updated</a>
		</th>
		<th class="smalltext">
			<a href="?page={$smarty.get.page}&sort=date_added&dir={$oppDir}{$filter_str}">Date Added</a>
		</th>
		<th class="smalltext">
			<a href="?page={$smarty.get.page}&sort=active&dir={$oppDir}{$filter_str}">Active</a>
		</th>
		<th class="smalltext">
			<a href="?page={$smarty.get.page}&sort=groups&dir={$oppDir}{$filter_str}">Groups</a>
		</th>
		<th class="smalltext">
			<a href="?page={$smarty.get.page}&sort=lists&dir={$oppDir}{$filter_str}">Contact Lists</a>
		</th>
		<th class="smalltext">
			<a href="?page={$smarty.get.page}&sort=regions&dir={$oppDir}{$filter_str}">Regions</a>
		</th>
		<th class="smalltext">Edit</th>
	</tr>
	{section name=i loop=$users}
	<tr class="label {cycle values="altRow,NormalRow"}"> 	
		<td class="smalltext">{$users[i].user_id}</td>
		<td class="smalltext">{$users[i].username}</td>
		<td class="smalltext">{$users[i].first_name}</td>
		<td class="smalltext">{$users[i].last_name}</td>
		<td class="smalltext">{$users[i].email}</td>
		<td class="smalltext">{$users[i].last_updated}</td>
		<td class="smalltext">{$users[i].date_created}</td>
		<td class="smalltext">{$users[i].active}</td>
		<td class="smalltext">{$users[i].groups}</td>
		<td class="smalltext">{$users[i].lists}</td>
		<td class="smalltext">{$users[i].regions}</td>
		{if $super_admin || $users[i].user_id == $cur_user_id}
		<td class="smalltext">
			<a href="?page={$smarty.get.page}&action=edit&user_id={$users[i].user_id}">Edit</a>
		</td>
		{else}
			<td></td>
		{/if}
	</tr>		
	{/section}
</table>