<!-- customer_search_result_module -->
<h2>Customer Search</h2>
Use % as a wildcard character
<table class="edit_class">
	<form method="GET" action="/dts/index.php" name="" enctype="multipart/form-data">
		<input type="hidden" name="sml_view" />
		<input type="hidden" name="page" value="load" />
		<input type="hidden" name="action" value="customer_search_result" />
		<input type="hidden" name="load_id" value="{$smarty.get.load_id}" />
	<tr>
		<td class="label_class">
			Customer Id:
		</td>
		<td class="edit_class">
			<input type="text" name="customer_id" size="11" maxlength="11" value="{if isset($smarty.get.customer_id)}{$smarty.get.customer_id}{/if}" />
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
			<input type="submit" value="Search">
		</td>
	</tr>
	</form>
</table>
Customer Search Results<br/>
<script type="text/javascript">
					
	function set_customer(customer_id)
	{ldelim}
		var param_str = "table=load&action=update&customer_id="+customer_id+"&load_id=";
		var obj = {};
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
<div class="tableContainer" id="tableContainer" style="height: 295px;overflow: auto;margin: 0 auto;">
<table class="view list scrollTable" id="_portal" style="width: 99%;border: none;background-color: #f7f7f7;">
	<thead>
		<tr>
			<th>Customer&nbsp;Id</th>
			<th>Name</th>
			<th>Address</th>
			<th>City</th>
			<th>State</th>
			<th>Add</th>
		</tr>
	</thead>
	<tbody style="overflow: auto;overflow-x: hidden;">
		{section name=i loop=$cust}
		<tr id="_" class="normalRow">
			<td class="list_data customer_id" style="
						padding-right: 2px;
						text-align: left;
						font-weight:bold;
						font-size: 12px;
						color: black;">{$cust[i].p_customer_id}</td>
<td class="list_data name" style="
						padding-right: 2px;
						text-align: left;
						font-weight:bold;
						font-size: 12px;
						color: black;">{$cust[i].name}</td>
<td class="list_data address" style="
						padding-right: 2px;
						text-align: left;
						font-weight:bold;
						font-size: 12px;
						color: black;">{$cust[i].address}</td>
<td class="list_data city" style="
						padding-right: 2px;
						text-align: left;
						font-weight:bold;
						font-size: 12px;
						color: black;">{$cust[i].city}</td>
<td class="list_data state" style="
						padding-right: 2px;
						text-align: left;
						font-weight:bold;
						font-size: 12px;
						color: black;">{$cust[i].state}</td>
<td class="list_data add" style="
						padding-right: 2px;
						text-align: left;
						font-weight:bold;
						font-size: 12px;
						color: black;">
						
						{if $cust[i].account_status == "Active"}
						<input type="button" value="Add" onclick="set_customer({$cust[i].customer_id})" />
						{else}
						{$cust[i].account_status}
						{/if}
						</td>
		</tr>
		{/section}
	</tbody>
</table>

</div>
		<style>
			{literal}
			.normalRow
			{
				background-color:white;
				color:color;
			}
			.altRow
			{
				background-color:silver;
				color:color;
			}
			{/literal}
		</style>
<!-- customer_search_result_module -->