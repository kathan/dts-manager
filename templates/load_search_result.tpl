<!-- start search result -->
<h4>Load Search Results</h4>
<div class='tableContainer' id='tableContainer' style='height: 295px;overflow: auto;margin: 0 auto;'>
	<table class='view list scrollTable' id='_portal' style='width: 99%;border: none;background-color: #f7f7f7;'>
		<thead>
			<tr>
				<th>Load&nbsp;Id</th>
				<th>Origin</th>
				<th>Dest</th>
			</tr>
		</thead>
		<tbody style='overflow: auto;overflow-x: hidden;'>
		{section name=i loop=$loads}
			<tr id='load_{$loads[i].load_id}' class='normalRow' onclick="row_clicked('{$loads[i].load_id}', 'load_id', 'load')">
				<td class='list_data load_id' style='
						padding-right: 2px;
						text-align: left;
						font-weight:bold;
						font-size: 12px;
						color: black;'>
					<a class="{$loads[i].load_type}" href="?page=load&amp;action=Edit&amp;load_id={$loads[i].load_id}">
						{$loads[i].load_id}
					</a>
				</td>
				<td class='list_data origin' style='
						padding-right: 2px;
						text-align: left;
						font-weight:bold;
						font-size: 12px;
						color: black;'>
					<a href="?page=load&amp;action=Edit&amp;load_id={$loads[i].load_id}">
						{$loads[i].origin}
					</a>
				</td>
				<td class='list_data dest' style='
						padding-right: 2px;
						text-align: left;
						font-weight:bold;
						font-size: 12px;
						color: black;'>
					<a href="?page=load&amp;action=Edit&amp;load_id={$loads[i].load_id}">
						{$loads[i].dest}
					</a>
				</td>
			</tr>
		{/section}
		</tbody>
	</table>
</div>
		<style>
			.normalRow
			{ldelim}
				background-color:white;
			{rdelim}
			.altRow
			{ldelim}
				background-color:silver;
			{rdelim}
		</style>
<!-- end search result -->