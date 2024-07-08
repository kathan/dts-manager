<!-- start lane search-->

<h2>Lane Search</h2>
<form method="GET" action="index.php" name="" enctype="multipart/form-data">
	<input type="hidden" name="page" id=""  value="lanes">
	<table class="edit_class">
		<tr>
			<td class="label_class">
				Start Activity Date:
			</td>
			<td class="edit_class">
				<input type="text" value="{if isset($form.start_activity_date)}{$form.start_activity_date}{/if}" id="start_activity_date" name="start_activity_date" value="" size="10" readOnly="true" datechange="function(y,m,d){ldelim}db_save(this.id, this.value);{rdelim};">
				<img onClick="cal_start_activity_date.select(document.getElementById('start_activity_date'),'cal_button_start_activity_date','MM/dd/yyyy')" ID="cal_button_start_activity_date" src="images/cal.gif" style="vertical-align:middle">
				<span ID="cal_div_start_activity_date" style="background-color:white;position:absolute"></span>
			</td>
		</tr>
		<tr>
			<td class="label_class">
				End Activity Date:
			</td>
			<td class="edit_class">
				<input type="text" value="{if isset($form.end_activity_date)}{$form.end_activity_date}{/if}" id="end_activity_date" name="end_activity_date" value="" size="10" readOnly="true" datechange="function(y,m,d){ldelim}db_save(this.id, this.value);{rdelim};">
				<img onClick="cal_end_activity_date.select(document.getElementById('end_activity_date'),'cal_button_end_activity_date','MM/dd/yyyy')" ID="cal_button_end_activity_date" src="images/cal.gif" style="vertical-align:middle">
				<span ID="cal_div_end_activity_date" style="background-color:white;position:absolute"></span>	
			</td>
		</tr>
		<tr>
			<td class="label_class">
				Pickup City:
			</td>
			<td class="edit_class">
				<input type="text" name="pickup_city" id=""  value="{if isset($form.pickup_city)}{$form.pickup_city}{/if}">
			</td>
		</tr>
		<tr>
			<td class="label_class">
				Pickup State:
			</td>
			<td class="edit_class">
				<input type="text" name="pickup_state" id=""  value="{if isset($form.pickup_state)}{$form.pickup_state}{/if}">
			</td>
		</tr>
		<tr>
			<td class="label_class">
				Dest City:
			</td>
			<td class="edit_class">
				<input type="text" name="dest_city" id=""  value="{if isset($form.dest_city)}{$form.dest_city}{/if}">
			</td>
		</tr>
		<tr>
			<td class="label_class">
				Dest State:
			</td>
			<td class="edit_class">
				<input type="text" name="dest_state" id=""  value="{if isset($form.dest_state)}{$form.dest_state}{/if}">
			</td>
		</tr>
		<tr>
			<td class="label_class">
				Load Type:
			</td>
			<td class="edit_class">
				{if isset($form.load_type)}
				{html_options name="load_type" options=$load_types selected=$form.load_type}
				{/if}
			</td>
		</tr>
		<tr>
			<td class="label_class">
			</td>
			<td class="edit_class">
				<input type="submit" name="Search" id=""  value="Search">
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">
	include("js/CalendarPopup.js");
	var cal_end_activity_date = new CalendarPopup("cal_div_end_activity_date");
	var cal_start_activity_date = new CalendarPopup("cal_div_start_activity_date");
</script>
<!-- end lane search -->
