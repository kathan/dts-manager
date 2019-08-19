<h2>Customer Search New</h2>
<form method="get" enctype="multipart/form-data">
	<input type="hidden" name="page" value="customer" />
	<input type="hidden" name="action" value="customer_search_result" />
	<table class="edit_class">
		<tr>
			<td class="label_class">
				Customer Id:
			</td>
			<td class="edit_class">
				<input type="text" name="customer_id" size="11" maxlength="11" value="" />
			</td>
		</tr>
		<tr>
			<td class="label_class">
				Name:
			</td>
			<td class="edit_class">
				<input type="text" name="name" maxlength="30" value="" />
			</td>
		</tr>
		<tr>
			<td class="label_class">
				City:
			</td>
			<td class="edit_class">
				<input type="text" name="city" maxlength="100" value="" />
			</td>
		</tr>
		<tr>
			<td class="label_class">
				State:
			</td>
			<td class="edit_class">
				<input type="text" name="state" size="2" maxlength="2" value="" />
			</td>
		</tr>
		<tr>
			<td class="label_class">
				Account Owner:
			</td>
			<td class="edit_class">
				{html_options name="acct_owner" options=$users selected=$smarty.get.acct_owner}
			</td>
		</tr>
		<tr>
			<td class="label_class"></td>
			<td class="edit_class">
				<input type="submit" value="Search" />
			</td>
		</tr>
	</table>
</form>