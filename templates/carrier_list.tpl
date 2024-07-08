<!-- carrier_search_result_module -->
<div class="center">
	<h2>Carrier Search</h2>
	<table class="edit_class">
		<form method="GET"  enctype="multipart/form-data">
			<input type="hidden" name="page" value="{$smarty.get.page}" />
			<input type="hidden" name="action" value="carrier_search_result" />
		<tr>
			<td class="label_class">
				Carrier Id:
			</td>
			<td class="edit_class">
				<input type="text" name="carrier_id" size="11" maxlength="11" value="{if isset($smarty.get.carrier_id)}{$smarty.get.carrier_id}{/if}" />
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
				<input type="text" name="phys_city" maxlength="100" value="{if isset($smarty.get.phys_city)}{$smarty.get.phys_city}{/if}" />
			</td>
		</tr>
		<tr>
			<td class="label_class">
				State:
			</td>
			<td class="edit_class">
				<input type="text" name="phys_state" size="2" maxlength="2" value="{if isset($smarty.get.phys_state)}{$smarty.get.phys_state}{/if}" />
			</td>
		</tr>
		<tr>
			<td class="label_class">
				MC Number:
			</td>
			<td class="edit_class">
				<input type="text" name="mc_number" value="{if isset($smarty.get.mc_number)}{$smarty.get.mc_number}{/if}" />
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
	<form method="GET" target="carrier_print_view">
		<input type="hidden" name="sml_view" />
		<input type="hidden" name="page" value="{$smarty.get.page}" />
		<input type="hidden" name="carrier_id" value="{$smarty.get.carrier_id}" />
		<input type="hidden" name="name" value="{$smarty.get.name}" />
		<input type="hidden" name="phys_address" value="{$smarty.get.phys_address}" />
		<input type="hidden" name="phys_city" value="{$smarty.get.phys_city}" />
		<input type="hidden" name="phys_state" value="{$smarty.get.phys_state}" />
		<input type="hidden" name="action" value="print" />
		<input type="submit" value="Print" />
	</form>
	{/if}
</div>
<script type="text/javascript">
	{*ldelim}		
	function set_carrier(carrier_id)
	
		var param_str = "table=load&action=update&carrier_id="+carrier_id+"&load_id=";
		var obj = {};
		obj.id = param_str;
		obj.value = {$smarty.get.load_id};
		db_save(obj);
		
		refresh_close();
	{rdelim}
	function refresh_close()
	{ldelim}
		window.opener.update_carrier_module();
		window.close();
	{rdelim*}
</script>
<table class="view list scrollTable" id="_portal" style="width: 99%;border: none;background-color: #f7f7f7;">
	<thead>
		<tr>
			<th>Carrier&nbsp;Id</th>
			<th>Name</th>
			<th>Address</th>
			<th>City</th>
			<th>State</th>
		</tr>
	</thead>
	<tbody >
		{section name=i loop=$carrier}
		<tr id="_" class="{cycle values="normalRow,altRow"}" onclick="row_clicked('{$carrier[i].carrier_id}', 'carrier_id', '\carrier')">
			<td class="list_data carrier_id">{$carrier[i].id}</td>
			<td class="list_data name">{$carrier[i].name}</td>
			<td class="list_data phys_address">{$carrier[i].phys_address}</td>
			<td class="list_data phys_city">{$carrier[i].phys_city}</td>
			<td class="list_data phys_state">{$carrier[i].phys_state}</td>
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

<!-- carrier_search_result_module -->