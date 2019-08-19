<a href="?page=cust_report">Customer Report</a>
{literal}
<style media="print" type="text/css">
	
	#selector, #print_button, .menu{display:none}
	#logo{visibility:visible}
</style>
<style media="screen" type="text/css">#logo{display:none}</style>
{/literal}	
<table id="selector">
	<tr>
		<th>Choose a Date</th>
		<th>Choose a User (Optional)</th>
		<th>WCP</th>
		<th>Load Type</th>
	</tr>
	<tr>
	<form method="get">
		<input type="hidden" name="page" value="{$page}">
		<td>
				{html_select_date prefix="start_" display_days=false start_year="2007" end_year=$year}
		</td>
		
		<td>
			{html_options name=user_id options=$users selected=$form.user_id}
		</td>
		<td>
			{if isset($form.wcp)}
			<input type="checkbox" name="wcp" checked="checked" />
			{else}
			<input type="checkbox" name="wcp" />
			{/if}
		</td>
		<td>
			{html_checkboxes name=load_type options=$load_types selected=$load_type selected=$form.load_type separator="<br/>"}
		</td>
	</tr>
	<tr>
		<td>
			<input type="submit" name="action" value="Report">
		</td>
		</form>
	</tr>
</table>