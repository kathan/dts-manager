
<table>
	<tr>
		<td>
			<div style="padding:3px;background-color:#eee">
				<h3 style="padding:3px;margin:0px;background-color:#ddd">Edit User</h3>
			<form method="POST" action="">
				{if isset($user.user_id)}<input type="hidden" name="user_id" value="{$user.user_id}">{/if}
				{if isset($smarty.server.HTTP_REFERER)}<input type="hidden" name="referer" value="{$smarty.server.HTTP_REFERER}"/>{/if}
				<input type="hidden" name="action" value="save"/>
				<table class="simpleframe">
			
					{*Fields*}
					<tr>
						<td class="label">First Name:</td>
						<td class="editstyle">
							<input type="text" name="first_name" value="{$user.first_name}" />
						</td>
					</tr>
					<tr>
						<td class="label">Last Name:</td>
						<td class="editstyle">
							<input type="text" name="last_name" value="{$user.last_name}" />
						</td>
					</tr>
					<tr>
						<td class="label">Email Address:</td>
						<td class="editstyle">
							<input type="text" name="email" value="{$user.email}" />
						</td>
					</tr>
					<tr>
						<td class="label">Username:</td>
						<td class="editstyle">
							<input type="text" name="username" value="{$user.username}" />
						</td>
					</tr>
					<tr>
						<td class="label">Password:</td>
						<td class="editstyle">
							<input type="password" name="password1" />
						</td>
					</tr>
					<tr>
						<td class="label">Confirm Password:</td>
						<td class="editstyle">
							<input type="password" name="password2" />
						</td>
					</tr>
					{if $admin}
					<tr>
						<td class="label">Active:</td>
						<td class="editstyle">
							<input type="hidden" name="active" />
						{if $user.active}
							<input type="checkbox" name="active" checked="checked" />
						{else}
							<input type="checkbox" name="active" />
						{/if}
						</td>
					</tr>
					{/if}
					{*Buttons*}
		
					<tr>
						<td>
							<input type="submit" name="action" value="Save"/>
						</td>
						<td>
							<input type="submit" name="action" value="Cancel"/>
						</td>
					</tr>
				</table>
			</form>
			</div>
		</td>
		<td>
		{if isset($group_form)}{$group_form}{/if}
		{if isset($contact_form)}{$contact_form}{/if}
		{if isset($region_form)}{$region_form}{/if}
		</td>
	</tr>
</table>