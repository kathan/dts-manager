<!-- get_load_warehouse_edit start -->
<table style="border:1px solid black">
	<tr>
		<th colspan="4">Load</td>
	</tr>
	<tr>
		<td>Load ID:</td>
		<td class="faux_edit">{$lw.load_id}</td>
		<td>Type:</td>
		<td class="faux_edit">{$lw.type}</td>
	</tr>
	<tr>
		<td>Activity Date:</td>
		<td>
			<input type="text" id="action=update&table=load_warehouse&warehouse_id={$lw.warehouse_id}&load_id={$lw.load_id}&activity_date=" name="activity_date" value="{$lw.activity_date}" size="10" readOnly="true" datechange="function(y,m,d){ldelim}db_save(this.id, this.value);{rdelim};" onchange="db_save(this);column_updated(this);" />

			<img onclick="cal_activity_date.select(document.getElementById('action=update&table=load_warehouse&warehouse_id={$lw.warehouse_id}&load_id={$lw.load_id}&activity_date='),'cal_button_activity_date','MM/dd/yyyy')" ID="cal_button_activity_date" src="images/cal.gif" style='vertical-align:middle' />
			<span id="cal_div_activity_date" style="background-color:white;position:absolute"></span>
				
		</td>
		<td>Open Time:</td>
		<td>
			{html_options name="open_time" id="action=update&table=load_warehouse&warehouse_id=`$lw.warehouse_id`&load_id=`$lw.load_id`&open_time="  onchange="db_save(this);column_updated(this);" selected=$lw.open_time options=$times}
		</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td>Close Time:</td>
		<td>
			{html_options name="close_time" id="action=update&table=load_warehouse&warehouse_id=`$lw.warehouse_id`&load_id=`$lw.load_id`&close_time="  onchange="db_save(this);column_updated(this);" selected=$lw.close_time options=$times}
		</td>
	</tr>
	<tr>
		<td>Creation Date:</td>
		<td class="faux_edit">{$lw.creation_date}</td>
		<td>Scheduled With:</td>
		<td class="faux_edit">{$scheduled_with}</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td>Owner:</td>
		<td class="faux_edit">{$order_by}</td>
	</tr>
</table>
<!-- edit warehouse start -->
<div>{$warehouse}</div>
<!-- edit warehouse end -->
<input type="button" onclick="window.close();" value="Close"/>
<script type="text/javascript">
	include('./CalendarPopup.js');
	var cal_activity_date = new CalendarPopup('cal_div_activity_date');
</script>
<!-- get_load_warehouse_edit end -->