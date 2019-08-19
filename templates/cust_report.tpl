{literal}
<style media="print" type="text/css">
	
	#selector, #print_button, .menu{display:none}
	#logo{visibility:visible}
</style>
<style media="screen" type="text/css">#logo{display:none}</style>
{/literal}
<h3>Customer Reports</h3>
<input type="button" id="print_button" value="Print" onclick="print();" />
<form>
	<input type="hidden" name="page" value="{$smarty.get.page}"/>
	<table>
		<tr>
			<th>
				User
			</th>
			<th>
				Start Date
			</th>
			<th>
				End Date
			</th>
		</tr>
		<tr>
			<td>
				{html_options name=user_id options=$users selected=$smarty.get.user_id}
			</td>
			<td>
				<input type="text" id="start_date" size="10" name="start_date" value="{$start_date}"/>
			</td>
			<td>
				<input type="text" id="end_date" size="10" name="end_date" value="{$end_date}"/>
			</td>
		</tr>
	</table>
	
<input type="submit" value="Report"/>
</form>
<h4>Results {if $username}for {$username} - {/if}{$start_date} to {$end_date}</h4>
<table width="100%">
	<tr>
		{*<th>Customer ID</th>*}
		<th><a href="?{$params|@array2query:'order':'dir'}&order=name&dir={if $params.dir == 'asc'}desc{else}asc{/if}">Customer</a></th>
		<th><a href="?{$params|@array2query:'order':'dir'}&order=cust_rep&dir={if $params.dir == 'asc'}desc{else}asc{/if}">Cust Rep</a></th>
		<th><a href="?{$params|@array2query:'order':'dir'}&order=load_count&dir={if $params.dir == 'asc'}desc{else}asc{/if}">Load Count</a></th>
		<th><a href="?{$params|@array2query:'order':'dir'}&order=gross_revenue&dir={if $params.dir == 'asc'}desc{else}asc{/if}">Gross Revenue</a></th>
		<th><a href="?{$params|@array2query:'order':'dir'}&order=profit&dir={if $params.dir == 'asc'}desc{else}asc{/if}">Profit</a></th>
	</tr>
	{section name=i loop=$cust}
		<tr class="{cycle values="altRow,normalRow"}">
			{*<td>{$cust[i].customer_id}</td>*}
			<td><a href="?action=Edit&page=customer&customer_id={$cust[i].customer_id}">{$cust[i].name}</a></td>
			<td>{$cust[i].cust_rep}</td>
			<td style="text-align:right">{$cust[i].load_count}</td>
			<td style="text-align:right;padding-right:12pt;">{$cust[i].gross_revenue|number_format:2:".":","}</td>
			<td style="text-align:right">{$cust[i].profit|number_format:2:".":","}</td>
		</tr>
		{math equation="$total_profit + p" assign="total_profit" p=$cust[i].profit}
		{math equation="$total_revenue + r" assign="total_revenue" r=$cust[i].gross_revenue}
		{math equation="$total_loads + lc" assign="total_loads" lc=$cust[i].load_count}
	{/section}
	<tr>
		{*<td></td>*}
		<td></td>
		<td style="font-weight:bold">Total</td>
		<td style="font-weight:bold;text-align:right">{$total_loads}</td>
		<td style="font-weight:bold;text-align:right;padding-right:12pt;">{$total_revenue|number_format:2:".":","}</td>
		<td style="font-weight:bold;text-align:right">{$total_profit|number_format:2:".":","}</td>
	</tr>
</table>

<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
<style>
{literal}
.ui-datepicker
{
	width:17em;
	font-size:8pt;
}
{/literal}
</style>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="scripts/jquery.ui.js"></script>
<script type="text/javascript">
{literal}
$(document).ready(function(e){
	$("#start_date").datepicker({dateFormat: 'yy-mm-dd'});
	$("#end_date").datepicker({dateFormat: 'yy-mm-dd'});
});


{/literal}
</script>