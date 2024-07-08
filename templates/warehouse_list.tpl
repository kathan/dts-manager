<!-- warehouse_search_result_module -->
<div class="center">
	<h2>Warehouse Search</h2>
	<table class="edit_class">
		<form method="GET"  enctype="multipart/form-data">
			<input type="hidden" name="page" value="{$smarty.get.page}" />
			<input type="hidden" name="action" value="warehouse_search_result" />
		<tr>
			<td class="label_class">
				Warehouse Id:
			</td>
			<td class="edit_class">
				<input type="text" name="warehouse_id" size="11" maxlength="11" value="{if isset($smarty.get.warehouse_id)}{$smarty.get.warehouse_id}{/if}" />
			</td>
		</tr>
		<tr>
			<td class="label_class">
				Name:
			</td>
			<td class="edit_class">
				<input type="text" name="name" maxlength="30" value="{if isset($smarty.get.name)}{$smarty.get.name}{/if}" />
			</td>
		</tr>
		<tr>
			<td class="label_class">
				City:
			</td>
			<td class="edit_class">
				<input type="text" name="city" maxlength="100" value="{if isset($smarty.get.city)}{$smarty.get.city}{/if}" />
			</td>
		</tr>
		<tr>
			<td class="label_class">
				State:
			</td>
			<td class="edit_class">
				<input type="text" name="state" size="2" maxlength="2" value="{if isset($smarty.get.state)}{$smarty.get.state}{/if}" />
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
	<form method="GET" target="warehouse_print_view">
		<input type="hidden" name="sml_view" />
		<input type="hidden" name="page" value="{$smarty.get.page}" />
		<input type="hidden" name="warehouse_id" value="{$smarty.get.warehouse_id}" />
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
					
	function set_warehouse(warehouse_id)
	{ldelim}
		var param_str = "table=load&action=update&warehouse_id="+warehouse_id+"&load_id=";
		var obj = {};
		obj.id = param_str;
		obj.value = '{$smarty.get.load_id}';
		db_save(obj);
		
		refresh_close();
	{rdelim}
	function refresh_close()
	{ldelim}
		window.opener.update_warehouse_module();
		window.close();
	{rdelim}
</script>
<table class="view list scrollTable" id="_portal" style="width: 99%;border: none;background-color: #f7f7f7;">
	<thead>
		<tr>
			<th>Warehouse&nbsp;Id</th>
			<th>Name</th>
			<th>Address</th>
			<th>City</th>
			<th>State</th>
		</tr>
	</thead>
	<tbody >
		{section name=i loop=$warehouse}
		<tr id="_" class="{cycle values="normalRow,altRow"}" onclick="row_clicked('{$warehouse[i].warehouse_id}', 'warehouse_id', '\warehouse')">
			<td class="list_data warehouse_id">{$warehouse[i].id}</td>
			<td class="list_data name">{$warehouse[i].name}</td>
			<td class="list_data address">{$warehouse[i].address}</td>
			<td class="list_data city">{$warehouse[i].city}</td>
			<td class="list_data state">{$warehouse[i].state}</td>
		</tr>
		{/section}
	</tbody>
</table>
	{if $pag.start > 1}
		<a href="?{array2query array=$filters exclude=['start']}&amp;start=1">First</a> | 
		<a href="?{array2query array=$filters exclude=['start']}&amp;start={$pag.prev_start}">Previous</a>
	{/if}
	
	{$pag.start} thru {$pag.end} of {$pag.total}
		{if $pag.next_start <= $pag.total}
			<a href="?{array2query array=$filters exclude=['start']}&amp;start={$pag.next_start}">Next</a> | 
			<a href="?{array2query array=$filters exclude=['start']}&amp;start={$pag.last_page_start}">Last</a>
		{/if}

<!-- warehouse_search_result_module -->