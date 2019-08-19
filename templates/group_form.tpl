<!-- Group Form Start -->
<div style="padding:3px;background-color:#eee;margin-bottom:3px">
	<h4 style="margin:0px;padding:3px;background-color:#ddd">Group Lists</h4>
	<form method="post" action="">
		<input type="hidden" name="user_id" value="{$user_id}" />
		<input type="hidden" name="object" value="groups" />
		<table style="border:0px;text-align:center" class="simpleframe">
			<tr>
				<td style="text-align:right;width:33%">
					<h5 style="margin:0px">Available Groups:</h5>
					{html_options name="unlinkedgroups[]" multiple="multiple" style="width:15em;height:5em" options=$unlinked_options}
				</td>
				<td style="width:1%">
					<input type="submit" name="action" value="Add"><br/>
					<input type="submit" name="action" value="Remove">
				</td>
				<td style="text-align:left;width:33%">
					<h5 style="margin:0px">Assigned Groups:</h5>
					{html_options name="linkedgroups[]" multiple="multiple" style="width:15em;height:5em" options=$linked_options}
				</td>
			</tr>
		</table>
	</form>
</div>
<!-- Group Form End -->