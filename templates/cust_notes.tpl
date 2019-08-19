<table class="view list scrollTable" id="_portal" style="width: 99%; border: medium none; background-color: rgb(247, 247, 247);">
	<thead>
		<tr>
			<th>Notes</th>
			<th>Last&nbsp;Updated</th>
		</tr>
	</thead>
	<tbody style="overflow-y: auto; overflow-x: hidden;">
		{section name=i loop=$notes}
		<tr id="customer_notes_{$notes[i].note_id}" class="{cycle values="altRow,normalRow"}">
			<td class="list_data notes" style="padding-right: 2px; text-align: left; font-weight: bold; font-size: 12px; color: black;">{$notes[i].notes}</td>
			<td class="list_data last_updated" style="padding-right: 2px; text-align: left; font-weight: bold; font-size: 12px; color: black;">{$notes[i].last_updated}</td>
		</tr>
		{/section}
	</tbody>
</table>
