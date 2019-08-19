<!-- customer_search_result_module -->
<div class="center">
	<h2>Customer Search</h2>
	<table class="edit_class">
		<form method="GET"  enctype="multipart/form-data">
			<input type="hidden" name="page" value="{$smarty.get.page}" />
			<input type="hidden" name="action" value="customer_search_result" />
		<tr>
			<td class="label_class">
				Customer Id:
			</td>
			<td class="edit_class">
				<input type="text" name="customer_id" size="11" maxlength="11" value="{$smarty.get.customer_id}" />
			</td>
		</tr>
		<tr>
			<td class="label_class">
				Name:
			</td>
			<td class="edit_class">
				<input type="text" name="name" maxlength="30" value="{$smarty.get.name}" />
			</td>
		</tr>
		<tr>
			<td class="label_class">
				City:
			</td>
			<td class="edit_class">
				<input type="text" name="city" maxlength="100" value="{$smarty.get.city}" />
			</td>
		</tr>
		<tr>
			<td class="label_class">
				State:
			</td>
			<td class="edit_class">
				<input type="text" name="state" size="2" maxlength="2" value="{$smarty.get.state}" />
			</td>
		</tr>
		<tr>
			<td class="label_class">
				Account Owner:
			</td>
			<td class="edit_class">
				<select name="acct_owner">
				{html_options options=$acct_owners selected=$sel_acct_owner}
				</select>
			</td>
		</tr>
		<tr>
			<td class="label_class"></td>
			<td class="edit_class">
				<input type="submit" value="Search" />
			</td>
		</tr>
		</form>
	</table>
	{if $admin}
	<form method="GET" target="cust_print_view">
		<input type="hidden" name="sml_view" />
		<input type="hidden" name="page" value="{$smarty.get.page}" />
		<input type="hidden" name="customer_id" value="{$smarty.get.customer_id}" />
		<input type="hidden" name="city" value="{$smarty.get.city}" />
		<input type="hidden" name="state" value="{$smarty.get.state}" />
		<input type="hidden" name="name" value="{$smarty.get.name}" />
		<input type="hidden" name="acct_owner" value="{$smarty.get.acct_owner}" />
		<input type="hidden" name="action" value="print" />
		<input type="submit" value="Print" />
	</form>
	{/if}
</div>
<script type="text/javascript">
					
	function set_customer(customer_id)
	{ldelim}
		var param_str = "table=load&action=update&customer_id="+customer_id+"&load_id=";
		var obj=new Object();
		obj.id = param_str;
		obj.value = {$smarty.get.load_id};
		db_save(obj);
		
		refresh_close();
	{rdelim}
	function refresh_close()
	{ldelim}
		window.opener.update_cust_module();
		window.close();
	{rdelim}
</script>
<table class="view list scrollTable" id="_portal" style="width: 99%;border: none;background-color: #f7f7f7;">
	<thead>
		<tr>
			<th>Customer&nbsp;Id</th>
			<th>Name</th>
			<th>Address</th>
			<th>City</th>
			<th>State</th>
		</tr>
	</thead>
	<tbody >
		{section name=i loop=$cust}
		<tr id="_" class="{cycle values="normalRow,altRow"}" onclick="row_clicked('{$cust[i].customer_id}', 'customer_id', '\customer')">
			<td class="list_data customer_id">{$cust[i].p_customer_id}</td>
			<td class="list_data name">{$cust[i].name}</td>
			<td class="list_data address">{$cust[i].address}</td>
			<td class="list_data city">{$cust[i].city}</td>
			<td class="list_data state">{$cust[i].state}</td>
		</tr>
		{/section}
	</tbody>
</table>
	{if $pag.start > 1}
		<a href="?{$filters|@array2query:'start'}&amp;start=1">First</a> | 
		<a href="?{$filters|@array2query:'start'}&amp;start={$pag.prev_start}">Previous</a>
	{/if}
	
	{$pag.start} thru {$pag.end} of {$pag.total}
		{if $pag.next_start <= $pag.total}
			<a href="?{$filters|@array2query:'start'}&amp;start={$pag.next_start}">Next</a> | 
			<a href="?{$filters|@array2query:'start'}&amp;start={$pag.last_page_start}">Last</a>
		{/if}

<!-- customer_search_result_module -->