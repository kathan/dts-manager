<form method="post" action="">
	<table style="border:0px" class="simpleframe">
		<tr>
			<th colspan="3" class="subhead">
				<center>Contact Lists</center>
			</th>
		</tr>
		<tr>
			<input type="hidden" name="page" value="users" />
			<input type="hidden" name="action" value="addcontacts" />
			<input type="hidden" name="user_id" value="{$smarty.request.user_id}" />
			<td style="text-align:right;width:33%">
				Available Lists:<br>
				{html_options name="unlinkedcontacts[]" multiple="multiple" style="width:15em;height:5em" options=$linked_options}
			</td>
			<td style="width:1%;vertical-align:middle">
				<input type="submit" value="Add"/><br/>
				</form>
				<form method="post" action="">
					<input type="hidden" name="page" value="users"/>
					<input type="hidden" name="action" value="removecontacts"/>
					<input type="hidden" name="user_id" value="{$smarty.request.user_id}"/>
					<input type="submit" value="Remove"/>
			</td>
			<td style="text-align:left;width:33%">
				Assigned Lists:<br>
				{html_options name="linkedcontacts[]" multiple="multiple" style="width:15em;height:5em" options=$unlinked_options}
			</td>
		</tr>
	</table>
</form>