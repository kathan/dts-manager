<!-- monthly start -->
<input type="button" id="print_button" value="Print" onclick="print();" />
<br/>
<img id="logo" src="images/dts.gif">
<div class="title">{$month_str} {$start_year}{if $user} for {$user}{/if}</div>

<table width="100%">
{section name=w loop=$month}
	<tr class="weekly_head">
		<td colspan="6">
			Week of {$month[w].start_month}/{$month[w].start_week_day} thru {$month[w].start_month}/{$month[w].end_week_day}
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

	{assign var='load_count' value=0}
	{assign var='cust_rate_sum' value=0}
	{assign var='carrier_rate_sum' value=0}
	{assign var='profit_sum' value=0}
	{assign var='carrier_rep_comm_sum' value=0}
	{assign var='sales_rep_comm_sum' value=0}
	{assign var='total_comm_sum' value=0}
	{assign var='total_comm' value=0}
	{section name=l loop=$month[w].loads}
			{assign var='load_count' value="`$load_count+1`"}
			{assign var='total_comm' value=0}
			<tr class="{cycle values="altRow,normalRow"}">
				<td class="date">
					<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].date}</a>
				</td>
				<td class="id">
					<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].id}</a>
				</td>
				<td class="customer">
					<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].customer}</a>
				</td>
				<td class="cust_rate">
					{assign var='cust_rate_sum' value="`$cust_rate_sum+$month[w].loads[l].cust_rate`"}
					<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].cust_rate|number_format:2:".":","}</a>
				</td>
				<td class="carrier_rate">
					{assign var='carrier_rate_sum' value="`$carrier_rate_sum+$month[w].loads[l].carrier_rate`"}
					<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].carrier_rate|number_format:2:".":","}</a>
				</td>
				<td class="profit">
					{assign var='profit_sum' value="`$profit_sum+$month[w].loads[l].profit`"}
					
					
					<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].profit|number_format:2:".":","}</a>
				</td>
				<td class="carrier_rep">
					{if $user_id && $user_id != $month[w].loads[l].carrier_rep_id}
						&nbsp;
					{else}
						<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].carrier_rep}</a>
					{/if}
				</td>
				<td class="carrier_rep_comm">
					{if $user_id && $user_id != $month[w].loads[l].carrier_rep_id}
						&nbsp;
					{else}
						{assign var=total_comm value="`$total_comm+$month[w].loads[l].carrier_rep_comm`"}
						{assign var=carrier_rep_comm_sum value="`$carrier_rep_comm_sum+$month[w].loads[l].carrier_rep_comm`"}
						<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].carrier_rep_comm|number_format:2:".":","}</a>
					{/if}
				</td>
				<td class="sales_rep">
					{if $user_id && $user_id != $month[w].loads[l].sales_rep_id}
						&nbsp;
					{else}
						<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].sales_rep}</a>
					{/if}
				</td>
				<td class="sales_rep_comm">
					{if $user_id && $user_id != $month[w].loads[l].sales_rep_id}
						&nbsp;
					{else}
						{assign var=total_comm value="`$total_comm+$month[w].loads[l].sales_rep_comm`"}
						{assign var=sales_rep_comm_sum value="`$sales_rep_comm_sum+$month[w].loads[l].sales_rep_comm`"}
						<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].sales_rep_comm|number_format:2:".":","}</a>
					{/if}
				</td>
				<td class="total_comm">
					{assign var=total_comm_sum value="`$total_comm_sum+$total_comm`"}
					<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$total_comm|number_format:2:".":","}</a>
				</td>
			</tr>
   			
			{/section}
		</tbody>
		<tbody class="summary">
			<tr>
				<td class="">Week {$smarty.section.w.index+1}</td>
				<td class="Total Loads:">Total Loads:</td>
				<td class="load_count">{$load_count}</td>
				<td class="cust_rate_sum">{$cust_rate_sum|number_format:2:".":","}</td>
				<td class="carrier_rate_sum">{$carrier_rate_sum|number_format:2:".":","}</td>
				<td class="profit_sum">{$profit_sum|number_format:2:".":","}</td>
				<td class="carrier_rep"></td>
				<td class="carrier_rep_comm_sum">{$carrier_rep_comm_sum|number_format:2:".":","}</td>
				<td class="sales_rep"></td>
				<td class="sales_rep_comm_sum">{$sales_rep_comm_sum|number_format:2:".":","}</td>
				<td class="total_comm_sum">{$total_comm_sum|number_format:2:".":","}</td>
			</tr>
		</tbody>
		{assign var='load_count_gsum' value="`$load_count_gsum+$load_count`"}
		{assign var='cust_rate_gsum' value="`$cust_rate_gsum+$cust_rate_sum`"}
		{assign var='carrier_rate_gsum' value="`$carrier_rate_gsum+$carrier_rate_sum`"}
		{assign var='profit_gsum' value="`$profit_gsum+$profit_sum`"}
		{assign var='carrier_rep_comm_gsum' value="`$carrier_rep_comm_gsum+$carrier_rep_comm_sum`"}
		{assign var='sales_rep_comm_gsum' value="`$sales_rep_comm_gsum+$sales_rep_comm_sum`"}
		{assign var='total_comm_gsum' value="`$total_comm_gsum+$total_comm_sum`"}
   		
	{/section}
<tbody class="summary">
			<tr>
				<td class=""></td>
				<td class="Total Loads:">Total Loads:</td>
				<td class="load_count">{$load_count_gsum}</td>
				<td class="cust_rate_sum">{$cust_rate_gsum|number_format:2:".":","}</td>
				<td class="carrier_rate_sum">{$carrier_rate_gsum|number_format:2:".":","}</td>
				<td class="profit_sum">{$profit_gsum|number_format:2:".":","}</td>
				<td class="carrier_rep"></td>
				<td class="carrier_rep_comm_sum">{$carrier_rep_comm_gsum|number_format:2:".":","}</td>
				<td class="sales_rep"></td>
				<td class="sales_rep_comm_sum">{$sales_rep_comm_gsum|number_format:2:".":","}</td>
				<td class="total_comm_sum">{$total_comm_gsum|number_format:2:".":","}</td>
			</tr>
		</tbody>
</table>
<!-- monthly end -->