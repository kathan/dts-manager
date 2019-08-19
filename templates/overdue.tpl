<h3>Your Overdue Load Events</h3>
<table>
	<tr>
		<th>
			Load ID
		</th>
		<th>
			Activity Date
		</th>
		<th>
			Type
		</th>
		<th>
			Name
		</th>
		<th>
			City
		</th>
		<th>
			State
		</th>
		<th>
			Order By
		</th>
		<th>
			Acct Owner
		</th>
		<th>
			Closes At
		</th>
		<th>
			Urgency
		</td>
	</tr>
	{section name=i loop=$o}
	<tr>
		<td>
			<a href="?action=Edit&page=load&load_id={$o[i].load_id}">{$o[i].load_id}</a>
		</td>
		<td>
			{$o[i].activity_date}
		</td>
		<td>
			{$o[i].type}
		</td>
		<td>
			{$o[i].name}
		</td>
		<td>
			{$o[i].city}
		</td>
		<td>
			{$o[i].state}
		</td>
		<td>
			{$o[i].order_by}
		</td>
		<td>
			{$o[i].acct_owner}
		</td>
		<td>
			{$o[i].closes_at}
		</td>
		<td>
			{$o[i].urgency}
		</td>
	</tr>
	{/section}
	{section name=i loop=$a}
	<tr>
		<td>
			<a href="?action=Edit&page=load&load_id={$a[i].load_id}">{$a[i].load_id}</a>
		</td>
		<td>
			{$a[i].activity_date}
		</td>
		<td>
			{$a[i].type}
		</td>
		<td>
			{$a[i].name}
		</td>
		<td>
			{$a[i].city}
		</td>
		<td>
			{$a[i].state}
		</td>
		<td>
			{$a[i].order_by}
		</td>
		<td>
			{$a[i].acct_owner}
		</td>
		<td>
			{$a[i].closes_at}
		</td>
		<td>
			{$a[i].urgency}
		</td>
	</tr>
	{/section}
</table>