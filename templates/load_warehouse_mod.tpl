<table class="view list scrollTable" width="100%">
	<tr>
		<th>
			Type
		</th>
		<th>
			Open Time
		</th>
		<th>
			Close Time
		</th>
		<th>
			Act Date
		</th>
		<th>
			Complete
		</th>
		<th>
			Name
		</th>
		<th>
			Pick Dest Num
		</th>
		<th>
			City
		</th>
		<th>
			State
		</th>
		{if $admin}
		<th>
			Delete
		</th>
		{/if}
	</tr>
	{section name=i loop=$lw}
	<tr class="{cycle values="normalRow,altRow"}">
		<td class="list_data type">
			{$lw[i].type}
		</td>
		<td class="list_data type">
			{*<input type="text" id="action=update&table=load_warehouse&load_id={$lw[i].load_id}&warehouse_id={$lw[i].warehouse_id}&open_time=" name="open_time" value="{$lw[i].open_time}" size="10" readonly="true" onchange="db_save(this);column_updated(this);" />*}
			{html_options id="action=update&table=load_warehouse&load_id=`$lw[i].load_id`&warehouse_id=`$lw[i].warehouse_id`&open_time=" name="open_time" selected=$lw[i].open_time onchange="db_save(this);column_updated(this);" options=$times }
		</td>
		<td class="list_data type">
			{*$lw[i].close_time*}
			{html_options id="action=update&table=load_warehouse&load_id=`$lw[i].load_id`&warehouse_id=`$lw[i].warehouse_id`&close_time=" name="close_time" selected=$lw[i].close_time onchange="db_save(this);column_updated(this);" options=$times }
		</td>
		<td class="list_data type">
			{*act_date*}
			<input type="text" id="action=update&table=load_warehouse&load_id={$lw[i].load_id}&warehouse_id={$lw[i].warehouse_id}&activity_date=" name="activity_date" value="{if $lw[i].activity_date}{$lw[i].activity_date}{else}00/00/0000{/if}" size="10" readonly="true" onchange="db_save(this);column_updated(this);" />
			<img onclick="var e=document.getElementById('action=update&table=load_warehouse&load_id={$lw[i].load_id}&warehouse_id={$lw[i].warehouse_id}&activity_date=');var c_str='cal_div_act_date_{$lw[i].warehouse_id}_cal';cal_act_date_{$lw[i].warehouse_id}.select(e,'cal_button_act_date_{$lw[i].warehouse_id}','MM/dd/yyyy');" id="cal_button_act_date_{$lw[i].warehouse_id}" src="images/cal.gif" onload="include('js/CalendarPopup.js');cal_act_date_{$lw[i].warehouse_id} = new CalendarPopup('cal_div_act_date_{$lw[i].warehouse_id}');" style="vertical-align:middle" />
		
			<span id="cal_div_act_date_{$lw[i].warehouse_id}" style="background-color:white;position:absolute;z-index:1000" name="cal_act_date"></span> 
		</td>
		<td class="list_data type">
			{*complete*}	
			<input type="checkbox" id="action=update&table=load_warehouse&warehouse_id={$lw[i].warehouse_id}&load_id={$lw[i].load_id}&complete=" name="complete" onchange="db_save(this);column_updated(this);update_warehouse_portal();" {if $lw[i].complete}checked="checked"{/if} />
		</td>
		<td class="list_data type">
			{*name*}
			<a href="#" onclick="javascript:popUp('?page=load_warehouse&action=Edit&load_id={$lw[i].load_id}&warehouse_id={$lw[i].warehouse_id}&sml_view', 'load_warehouse_{$lw[i].load_id}_{$lw[i].warehouse_id}', 550, 550)">{$lw[i].name}</a>
		</td>
		<td class="list_data type">
			<input type="text" id="action=update&table=load_warehouse&warehouse_id={$lw[i].warehouse_id}&load_id={$lw[i].load_id}&pick_dest_num=" value="{$lw[i].pick_dest_num}" onchange="db_save(this);return column_updated(this);"/>
		</td>
		<td class="list_data type">
			{$lw[i].city}
		</td>
		<td class="list_data type">
			{$lw[i].state}
		</td>
		{if $admin}
		<td class="list_data type">
			<img src="images/delete.gif" onclick="delete_warehouse({$lw[i].warehouse_id})" />
		</td>
		{/if}
	</tr>
	{/section}
</table>
<style>
{literal}
#load_warehouse_portal .tableContainer{height:99% !important;}
		#load_warehouse_portal table>tbody{height:3em !important;}
{/literal}
</style>