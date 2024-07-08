<h2 class="center">D{$w.warehouse_id}</h2>
	<form method="post">
		<input type="hidden" name="action" value="{$wh_to_cust}">
		<input type="hidden" name="page" value="customer">
		<input type="hidden" name="warehouse_id" value="{$w.warehouse_id}">
		<input type="submit" value="Add As Customer">
	</form>
		<table width="100%">
			<tr>
				<td>
			
		
		<table width="100%"><tr><td>
			<tr>
				<td>Name:</td>
				<td><input type="text" name="name" value="{if isset($w.name)}{$w.name}{/if}" id="action={$action}&table=warehouse&warehouse_id={$w.warehouse_id}&name=" onchange="db_save(this);column_updated(this);" /></td>
			</tr>
			<tr>
				<td>Address:</td>
				<td><input type="text" name="address" value="{if isset($w.address)}{$w.address}{/if}" id="action={$action}&table=warehouse&warehouse_id={$w.warehouse_id}&address=" onchange="db_save(this);column_updated(this);" /></td></tr>
			<tr>
				<td>City:</td>
				<td><input type="text" name="city" value="{if isset($w.city)}{$w.city}{/if}" id="action={$action}&table=warehouse&warehouse_id={$w.warehouse_id}&city=" onchange="db_save(this);column_updated(this);" /></td>
			</tr>
			<tr>
				<td>State:</td>
				<td><input type="text" name="state" value="{if isset($w.state)}{$w.state}{/if}" id="action={$action}&table=warehouse&warehouse_id={$w.warehouse_id}&state=" onchange="db_save(this);column_updated(this);" /></td>
			</tr>
			<tr>
				<td>Zip:</td>
				<td><input type="text" name="zip" value="{if isset($w.zip)}{$w.zip}{/if}" id="action={$action}&table=warehouse&warehouse_id={$w.warehouse_id}&zip=" onchange="db_save(this);column_updated(this);" /></td>
			</tr>
			<tr>
				<td>Phone:</td>
				<td><input type="text" name="phone" value="{if isset($w.phone)}{$w.phone}{/if}" id="action={$action}&table=warehouse&warehouse_id={$w.warehouse_id}&phone=" onchange="db_save(this);column_updated(this);" /></td>
			</tr>
			<tr>
				<td>Fax:</td>
				<td><input type="text" name="fax" value="{if isset($w.fax)}{$w.fax}{/if}" id="action={$action}&table=warehouse&warehouse_id={$w.warehouse_id}&fax=" onchange="db_save(this);column_updated(this);" /></td>
			</tr>
			<tr>
				<td>Notes:</td>
				<td><textarea name="notes" id="action={$action}&table=warehouse&warehouse_id={$w.warehouse_id}&notes=" onchange="db_save(this);column_updated(this);">{$w.notes}</textarea></td>
			</tr>
			<tr>
				<td>Directions:</td>
				<td><textarea name="directions" id="action={$action}&table=warehouse&warehouse_id={$w.warehouse_id}&directions="  onchange="db_save(this);column_updated(this);">{$w.directions}</textarea></td>
			</tr>
		</table>
		</td>

		<td valign="top">
		<table>
					<tr>
						<th></th>
						<th>
							Open Time
						</th>
						<th>
							Close Time
						</th>
					</tr>
		
					<tr>
						<td>Sun</td>
						<td>{html_options
								options=$times
								name="sun_open_time"
								selected=$w.sun_open_time
								id="action=`$action`&table=warehouse&warehouse_id=`$w.warehouse_id`&sun_open_time="
								onchange="db_save(this);column_updated(this);"
								}</td>
						<td>{html_options
								options=$times
								name="sun_close_time"
								selected=$w.sun_close_time
								id="action=`$action`&table=warehouse&warehouse_id=`$w.warehouse_id`&sun_close_time="
								onchange="db_save(this);column_updated(this);"
								}</td>
					</tr>
		
					<tr>
						<td>Mon</td>
						<td>{html_options
								options=$times
								name="mon_open_time"
								selected=$w.mon_open_time
								id="action=`$action`&table=warehouse&warehouse_id=`$w.warehouse_id`&mon_open_time="
								onchange="db_save(this);column_updated(this);"
								}</td>
						<td>{html_options
								options=$times
								name="mon_close_time"
								selected=$w.mon_close_time
								id="action=`$action`&table=warehouse&warehouse_id=`$w.warehouse_id`&mon_close_time="
								onchange="db_save(this);column_updated(this);"
								}</td>
					</tr>
		
					<tr>
						<td>Tues</td>
						<td>{html_options
								options=$times
								name="tues_open_time"
								selected=$w.tues_open_time
								id="action=`$action`&table=warehouse&warehouse_id=`$w.warehouse_id`&tues_open_time="
								onchange="db_save(this);column_updated(this);"
								}</td>
						<td>{html_options
								options=$times
								name="tues_close_time"
								selected=$w.tues_close_time
								id="action=`$action`&table=warehouse&warehouse_id=`$w.warehouse_id`&tues_close_time="
								onchange="db_save(this);column_updated(this);"
								}</td>
					</tr>
		
					<tr>
						<td>Wed</td>
						<td>{html_options
								options=$times
								name="wed_open_time"
								selected=$w.wed_open_time
								id="action=`$action`&table=warehouse&warehouse_id=`$w.warehouse_id`&wed_open_time="
								onchange="db_save(this);column_updated(this);"
								}</td>
						<td>{html_options
								options=$times
								name="wed_close_time"
								selected=$w.wed_close_time
								id="action=`$action`&table=warehouse&warehouse_id=`$w.warehouse_id`&wed_close_time="
								onchange="db_save(this);column_updated(this);"
								}</td>
					</tr>
		
					<tr>
						<td>Thurs</td>
						<td>{html_options
								options=$times
								name="thurs_open_time"
								selected=$w.thurs_open_time
								id="action=`$action`&table=warehouse&warehouse_id=`$w.warehouse_id`&thurs_open_time="
								onchange="db_save(this);column_updated(this);"
								}</td>
						<td>{html_options
								options=$times
								name="thurs_close_time"
								selected=$w.thurs_close_time
								id="action=`$action`&table=warehouse&warehouse_id=`$w.warehouse_id`&thurs_close_time="
								onchange="db_save(this);column_updated(this);"
								}</td>
					</tr>
		
					<tr>
						<td>Fri</td>
						<td>{html_options
								options=$times
								name="fri_open_time"
								selected=$w.fri_open_time
								id="action=`$action`&table=warehouse&warehouse_id=`$w.warehouse_id`&fri_open_time="
								onchange="db_save(this);column_updated(this);"
								}</td>
						<td>{html_options
								options=$times
								name="fri_close_time"
								selected=$w.fri_close_time
								id="action=`$action`&table=warehouse&warehouse_id=`$w.warehouse_id`&fri_close_time="
								onchange="db_save(this);column_updated(this);"
								}</td>
					</tr>
		
					<tr>
						<td>Sat</td>
						<td>{html_options
								options=$times
								name="sat_open_time"
								selected=$w.sat_open_time
								id="action=`$action`&table=warehouse&warehouse_id=`$w.warehouse_id`&sat_open_time="
								onchange="db_save(this);column_updated(this);"
								}</td>
						<td>{html_options
								options=$times
								name="sat_close_time"
								selected=$w.sat_close_time
								id="action=`$action`&table=warehouse&warehouse_id=`$w.warehouse_id`&sat_close_time="
								onchange="db_save(this);column_updated(this);"
								}</td>
					</tr>
		</table></td></tr>

		</tr></table>
		{if $new}
		
			<input type="button" onclick="cancel();" value="Cancel">
			<script type="text/javascript">
			
						function cancel()
						{literal}{
							if(confirm("Are you sure you want to cancel?"))
							{
							{/literal}
								window.location="?action={$delete_str}&page=warehouse&warehouse_id={$w.warehouse_id}";
							{literal}
							}
						}
						{/literal}
			</script>
		{/if}
			<input type="button" onclick="window.location = '?page=warehouse'" value="Save">
			
		