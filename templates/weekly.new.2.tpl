<!-- weekly start -->
		<tr class="weekly_head">
				<td colspan="6">
					Week of {$start_month}/{$start_week_day} thru {$start_month}/{$end_week_day}
				</td>
		</tr>
		<tbody class="weekly">
			<tr>
				<th>Date</th>
   				<th>Id</th>
   				<th>Customer</th>
   				<th>Cust Rate</th>
   				<th>Carrier Rate</th>
   				<th>Profit</th>
   				<th>Carrier Rep</th>
   				<th>Carrier Rep Comm</th>
   				<th>Sales Rep</th>
   				<th>Sales Rep Comm</th>
   				<th>Total Comm</th>
   			</tr>
   			{*summary vals*}
   			{assign var='total_comm_sum' value=0}
   			{assign var='load_count' value=0}
   			{assign var='cust_rate_sum' value=0}
   			{section name=i loop=$weeks}
   			{assign var='load_count' value=`$load_count+1`}
   			{assign var='total_comm' value=0}
			<tr>
				<td class="date">
   					<a href="?action=Edit&page=load&load_id={$weeks[i].load_id}">{$weeks[i].date}</a>
				</td>
				<td class="id">
   					<a href="?action=Edit&page=load&load_id={$weeks[i].load_id}"><span class="TL">{$weeks[i].id}</span></a>
				</td>
				<td class="customer">
   					<a href="?action=Edit&page=load&load_id={$weeks[i].load_id}">{$weeks[i].customer}</a>
				</td>
				<td class="cust_rate">
					{assign var='cust_rate_sum' value=`$cust_rate_sum+$weeks[i].cust_rate`}
   					<a href="?action=Edit&page=load&load_id={$weeks[i].load_id}">{$weeks[i].cust_rate}</a>
				</td>
				<td class="carrier_rate">
					{assign var='carrier_rate_sum' value=`$carrier_rate_sum+$weeks[i].carrier_rate`}
   					<a href="?action=Edit&page=load&load_id={$weeks[i].load_id}">{$weeks[i].carrier_rate}</a>
				</td>
				<td class="profit">
					{assign var='profit_sum' value=`$profit_sum+$weeks[i].profit`}
   					<a href="?action=Edit&page=load&load_id={$weeks[i].load_id}">{$weeks[i].profit}</a>
				</td>
				<td class="carrier_rep">
					{if $user_id && $user_id != $weeks[i].carrier_rep_id}
						&nbsp;
					{else}
   						<a href="?action=Edit&page=load&load_id={$weeks[i].load_id}">{$weeks[i].carrier_rep}</a>
   					{/if}
				</td>
				<td class="carrier_rep_comm">
					{if $user_id && $user_id != $weeks[i].carrier_rep_id}
						&nbsp;
					{else}
						{assign var=total_comm value=`$total_comm+$weeks[i].carrier_rep_comm`}
						{assign var=carrier_rep_comm_sum value=`$carrier_rep_comm_sum+$weeks[i].carrier_rep_comm`}
   						<a href="?action=Edit&page=load&load_id={$weeks[i].load_id}">{$weeks[i].carrier_rep_comm}</a>
   					{/if}
				</td>
				<td class="sales_rep">
					{if $user_id && $user_id != $weeks[i].sales_rep_id}
						&nbsp;
					{else}
   						<a href="?action=Edit&page=load&load_id={$weeks[i].load_id}">{$weeks[i].sales_rep}</a>
   					{/if}
				</td>
				<td class="sales_rep_comm">
					{if $user_id && $user_id != $weeks[i].sales_rep_id}
						&nbsp;
					{else}
						{assign var=total_comm value=`$total_comm+$weeks[i].sales_rep_comm`}
						{assign var=sales_rep_comm_sum value=`$sales_rep_comm_sum+$weeks[i].sales_rep_comm`}
   						<a href="?action=Edit&page=load&load_id={$weeks[i].load_id}">{$weeks[i].sales_rep_comm}</a>
   					{/if}
				</td>
				<td class="total_comm">
					{assign var=total_comm_sum value=`$total_comm_sum+$total_comm`}
   					<a href="?action=Edit&page=load&load_id={$weeks[i].load_id}">{$total_comm}</a>
				</td>
   			</tr>
   			{/section}
		</tbody>
		<tbody class="summary">
			<tr>
				<td class=""></td>
				<td class="Total Loads:">Total Loads:</td>
				<td class="load_count">{$load_count}</td>
				<td class="cust_rate_sum">{$cust_rate_sum}</td>
				<td class="carrier_rate_sum">{$carrier_rate_sum}</td>
				<td class="profit_sum">{$profit_sum}</td>
				<td class="carrier_rep"></td>
				<td class="carrier_rep_comm_sum">{$carrier_rep_comm_sum}</td>
				<td class="sales_rep"></td>
				<td class="sales_rep_comm_sum">{$sales_rep_comm_sum}</td>
				<td class="total_comm_sum">{$total_comm_sum}</td>
			</tr>
		</tbody>
<!-- weekly end -->