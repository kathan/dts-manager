<!-- monthly start -->
{*1/20/14
remove columns
"carrier rep comm"
"sales rep"
"sales rep comm"
rename "total comm" to "profit"*}
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
   			<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
  			<th>Carrier Rep</th>
  			<th>WC</th>
   			{*<th>Carrier Rep Comm</th>
   			<th>Sales Rep</th>
   			<th>Sales Rep Comm</th>*}
   			<th>Profit</th>
   		</tr>
	{assign var=load_count_gsum value=0}
	{assign var=cust_rate_gsum value=0}
	{assign var=carrier_rate_gsum value=0}
	{assign var=profit_gsum value=0}
	{assign var=carrier_rep_comm_gsum value=0}
	{assign var=sales_rep_comm_gsum value=0}
	{assign var=sales_rep_comm_gsum value=0}
	{assign var=total_comm_gsum value=0}

	{assign var=load_count value=0}
   	{assign var='cust_rate_sum' value=0}
   	{assign var='carrier_rate_sum' value=0}
   	{assign var='profit_sum' value=0}
   	{assign var='carrier_rep_comm_sum' value=0}
   	{assign var='sales_rep_comm_sum' value=0}
   	{assign var='total_comm_sum' value=0}
   	{assign var='total_comm' value=0}
   	{section name=l loop=$month[w].loads}
   			{math assign=load_count load_count=$load_count equation="load_count+1"}
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
					{math assign=cust_rate_sum cust_rate_sum=$cust_rate_sum cust_rate=$month[w].loads[l].cust_rate equation="cust_rate_sum + cust_rate"}
   					<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].cust_rate|number_format:2:".":","}</a>
				</td>
				<td class="carrier_rate">
					{math assign=carrier_rate_sum carrier_rate_sum=$carrier_rate_sum carrier_rate=$month[w].loads[l].carrier_rate equation="carrier_rate_sum + carrier_rate"}
   					<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].carrier_rate|number_format:2:".":","}</a>
				</td>
				<td class="profit">
					{math assign=profit_sum profit_sum=$profit_sum profit=$month[w].loads[l].profit equation="profit_sum + profit"}
					
					
   					<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].profit|number_format:2:".":","}</a>
				</td>
				<td></td>
				<td class="carrier_rep">
					{*if $user_id && $user_id != $month[w].loads[l].carrier_rep_id}
						&nbsp;
					{else*}
   						<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].carrier_rep}</a>
   					{*/if*}
				</td>
				<td class="wc_active">
					{$month[w].loads[l].wc_active}
				</td>
				{*<td class="carrier_rep_comm">*}
					{if $user_id && $user_id != $month[w].loads[l].carrier_rep_id}
						&nbsp;
					{else}
						{math assign=total_comm total_comm=$total_comm carrier_rep_comm=$month[w].loads[l].carrier_rep_comm equation="total_comm + carrier_rep_comm"}
						{math assign=carrier_rep_comm_sum carrier_rep_comm_sum=$carrier_rep_comm_sum carrier_rep_comm=$month[w].loads[l].carrier_rep_comm equation="carrier_rep_comm_sum + carrier_rep_comm"}
   						{*<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].carrier_rep_comm|number_format:2:".":","}</a>*}
   					{/if}
				{*</td>
				<td class="sales_rep">*}
					{if $user_id && $user_id != $month[w].loads[l].sales_rep_id}
						&nbsp;
					{else}
   						{*<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].sales_rep}</a>*}
   					{/if}
				{*</td>
				<td class="sales_rep_comm">*}
					{if $user_id && $user_id != $month[w].loads[l].sales_rep_id}
						&nbsp;
					{else}
						{if $user_id && $user_id != $month[w].loads[l].carrier_rep_id}
							{assign var=total_comm value=0}
						{else}
							{assign var=total_comm value=$month[w].loads[l].profit}
						{/if}
						{math assign=sales_rep_comm_sum sales_rep_comm_sum=$sales_rep_comm_sum sales_rep_comm=$month[w].loads[l].sales_rep_comm equation="sales_rep_comm_sum + sales_rep_comm"}
   						{*<a href="?action=Edit&page=load&load_id={$month[w].loads[l].load_id}">{$month[w].loads[l].sales_rep_comm|number_format:2:".":","}</a>*}
   					{/if}
				{*</td>*}
				<td class="total_comm">
					{math assign=total_comm_sum total_comm_sum=$total_comm_sum total_comm=$total_comm equation="total_comm_sum + total_comm"}
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
				<td></td>
				<td class="carrier_rep"></td>
				<td></td>
				{*<td class="carrier_rep_comm_sum">{$carrier_rep_comm_sum|number_format:2:".":","}</td>
				<td class="sales_rep"></td>
				<td class="sales_rep_comm_sum">{$sales_rep_comm_sum|number_format:2:".":","}</td>*}
				<td class="total_comm_sum">{$total_comm_sum|number_format:2:".":","}</td>
			</tr>
		</tbody>
		{math assign=load_count_gsum load_count_gsum=$load_count_gsum load_count=$load_count equation="load_count_gsum + load_count"}
   		{math assign=cust_rate_gsum cust_rate_gsum=$cust_rate_gsum cust_rate_sum=$cust_rate_sum equation="cust_rate_gsum + cust_rate_sum"}
   		{math assign=carrier_rate_gsum carrier_rate_gsum=$carrier_rate_gsum carrier_rate_sum=$carrier_rate_sum equation="carrier_rate_gsum + carrier_rate_sum"}
   		{math assign=profit_gsum profit_gsum=$profit_gsum profit_sum=$profit_sum equation="profit_gsum + profit_sum"}
   		{math assign=carrier_rep_comm_gsum equation="$carrier_rep_comm_gsum+$carrier_rep_comm_sum"}
   		{math assign=sales_rep_comm_gsum sales_rep_comm_gsum=$sales_rep_comm_gsum sales_rep_comm_sum=$sales_rep_comm_sum equation="sales_rep_comm_gsum + sales_rep_comm_sum"}
   		{math assign=total_comm_gsum total_comm_gsum=$total_comm_gsum total_comm_sum=$total_comm_sum equation="total_comm_gsum + total_comm_sum"}
   		
	{/section}
<tbody class="summary">
			<tr>
				<td class=""></td>
				<td class="Total Loads:">Total Loads:</td>
				<td class="load_count">{$load_count_gsum}</td>
				<td class="cust_rate_sum">{$cust_rate_gsum|number_format:2:".":","}</td>
				<td class="carrier_rate_sum">{$carrier_rate_gsum|number_format:2:".":","}</td>
				<td class="profit_sum">{$profit_gsum|number_format:2:".":","}</td>
				<td></td>
				<td class="carrier_rep"></td>
				<td></td>
				{*<td class="carrier_rep_comm_sum">{$carrier_rep_comm_gsum|number_format:2:".":","}</td>
				<td class="sales_rep"></td>
				<td class="sales_rep_comm_sum">{$sales_rep_comm_gsum|number_format:2:".":","}</td>*}
				<td class="total_comm_sum">{$total_comm_gsum|number_format:2:".":","}</td>
			</tr>
		</tbody>
</table>
<!-- monthly end -->