<div class="tableContainer" id="tableContainer" style="height: 295px;overflow: auto;margin: 0 auto;">
	<table class="view list scrollTable" id="_portal" style="width: 99%;border: none;background-color: #f7f7f7;">
		<thead>
			<tr>
				<th>Id</th>
				<th>Origin</th>
				<th>Dest</th>
			</tr>
		</thead>
		<tbody style="overflow: auto;overflow-x: hidden;">
		{section name=i loop=$loads}
			<tr id="load_{$loads[i].load_id}" class="{cycle values="altRow,normalRow"}" onclick="row_clicked('{$loads[i].load_id}', 'load_id', 'load')">
				<td class="list_data id" style="padding-right: 2px;text-align: left;font-weight:bold;font-size: 12px;color: black;">
					<span class="{$loads[i].load_type}">{$loads[i].id}</span>
				</td>
				<td class="list_data origin" style="padding-right: 2px;text-align: left;font-weight:bold;font-size: 12px;color: black;">
					{if $loads[i].cancelled}<span class="cancelled">{elseif $loads[i].rating == "Expedited"}<span class="expedited">{/if}
					{$loads[i].origin}
					{if $loads[i].cancelled || $loads[i].rating == "Expedited"}</span>{/if}
				</td>
				<td class="list_data dest" style="padding-right: 2px;text-align: left;font-weight:bold;font-size: 12px;color: black;">
					{if $loads[i].cancelled}<span class="cancelled">{elseif $loads[i].rating == "Expedited"}<span class="expedited">{/if}
					{$loads[i].dest}
					{if $loads[i].cancelled || $loads[i].rating == "Expedited"}</span>{/if}
				</td>
			</tr>
		{/section}
		</tbody>
	</table>
</div>