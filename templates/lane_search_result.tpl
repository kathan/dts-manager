<center>
	<h2>Lane Search Results</h2>
	<div class="tableContainer" id="tableContainer" style="height: 295px;overflow: auto;margin: 0 auto;">
		<table class="view list scrollTable" id="_portal" style="width: 99%;border: none;background-color: #f7f7f7;">
			<thead>
				<tr>
					<th>Load&nbsp;ID</th>
					<th>Pickup</th>
					<th>Destination</th>
					
				</tr>
			</thead>
			<tbody style="overflow: auto;overflow-x: hidden;">
				{section name=i loop=$loads}
				<tr id="" class="normalRow">
					<td class="list_data load_type" style="
						padding-right: 2px;
						text-align: left;
						font-weight:bold;
						font-size: 12px;
						color: black;">
						<a class="{$loads[i].load_type}" href="?page=load&amp;action=Edit&amp;load_id={$loads[i].load_id}">
							{$loads[i].load_id}
						</a>
					</td>
					<td class="list_data pickup" style="
						padding-right: 2px;
						text-align: left;
						font-weight:bold;
						font-size: 12px;
						color: black;">
						<a href="?page=load&amp;action=Edit&amp;load_id={$loads[i].load_id}">
							{$loads[i].pickup}
						</a>
					</td>
					<td class="list_data destination" style="
						padding-right: 2px;
						text-align: left;
						font-weight:bold;
						font-size: 12px;
						color: black;">
						<a href="?page=load&amp;action=Edit&amp;load_id={$loads[i].load_id}">
							{$loads[i].destination}
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
		color:color;
	{rdelim}
	.altRow
	{ldelim}
		background-color:silver;
		color:color;
	{rdelim}
</style>
