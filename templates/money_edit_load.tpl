<table width='100%' border=0>
	<tr>
		<td width='50%'>
			<table width='100%' border=0>
				<tr>
					<th colspan=4>Customer</th>
				</tr>
				<tr>
					<th colspan=1></th>
					<th colspan=1>Amount</th>
					<th colspan=1>Rate</th>
					<th colspan=1>Extended</th>
				</tr>
				<tr>
					<td>Line Haul:</td>
					<td><input type="text" id="cust_line_haul_amount" name="cust_line_haul_amount" value="{$load.cust_line_haul_amount}" /></td>
					<td><input type="text" id="cust_line_haul" name="cust_line_haul" value="{$load.cust_line_haul}" /></td>
					<td class="right faux_edit" id="cust_line_haul_extended"></td>
				</tr>
				<tr>
					<td>Detention:</td>
					<td><input type="text" id="cust_detention_amount" name="cust_detention_amount" value="{$load.cust_detention_amount}" /></td>
					<td><input type="text" id="cust_detention" name="cust_detention" value="{$load.cust_detention}" /></td>
					<td class="right faux_edit" id="cust_detention_extended"></td>
				</tr>
				<tr>
					<td>TONU</td>
					<td><input type="text" id="cust_tonu_amount" name="cust_tonu_amount" value="{$load.cust_tonu_amount}" /></td>
					<td><input type="text" id="cust_tonu" name="cust_tonu" value="{$load.cust_tonu}" /></td>
					<td class="right faux_edit" id="cust_tonu_extended"></td>
				</tr>
				<tr>
					<td>Unload/Load</td>
					<td><input type="text" id="cust_unload_load_amount" name="cust_unload_load_amount" value="{$load.cust_unload_load_amount}" /></td>
					<td><input type="text" id="cust_unload_load" name="cust_unload_load" value="{$load.cust_unload_load}" /></td>
					<td class="right faux_edit" id="cust_unload_load_extended"></td>
				</tr>
				<tr>
					<td>Fuel</td>
					<td><input type="text" id="cust_fuel_amount" name="cust_fuel_amount" value="{$load.cust_fuel_amount}" /></td>
					<td><input type="text" id="cust_fuel" name="cust_fuel" value="{$load.cust_fuel}" /></td>
					<td class="right faux_edit" id="cust_fuel_extended"></td>
				</tr>
				<tr>
					<td>Other</td>
					<td><input type="text" id="cust_other_amount" name="cust_other_amount" value="{$load.cust_other_amount}" /></td>
					<td><input type="text" id="cust_other" name="cust_other" value="{$load.cust_other}" /></td>
					<td class="right faux_edit" id="cust_other_extended"></td>
				</tr>
			</table>
		</td>
		<td>
			<table width='100%' border=0>
				<tr>
					<th colspan=4>Carrier</th>
				</tr>
				<tr>
					<th colspan=1></th>
					<th colspan=1>Amount</th>
					<th colspan=1>Rate</th>
					<th colspan=1>Extended</th>
				</tr>
				<tr>
					<td>Line Haul:</td>
					<td><input type="text" id="carrier_line_haul_amount" name="carrier_line_haul_amount" value="{$load.carrier_line_haul_amount}" /></td>
					<td><input type="text" id="carrier_line_haul" name="carrier_line_haul" value="{$load.carrier_line_haul}" /></td>
					<td class="right faux_edit" id="carrier_line_haul_extended"></td>
				</tr>
				<tr>
		
		<td>Detention:</td>
		<td><input type="text" id="carrier_detention_amount" name="carrier_detention_amount" value="{$load.carrier_detention_amount}" /></td>
		<td><input type="text" id="carrier_detention" name="carrier_detention" value="{$load.carrier_detention}" /></td>
		<td class="right faux_edit" id="carrier_detention_extended"></td>
		</tr><tr>
		
		<td>TONU</td>
		<td><input type="text" id="carrier_tonu_amount" name="carrier_tonu_amount" value="{$load.carrier_tonu_amount}" /></td>
		<td><input type="text" id="carrier_tonu" name="carrier_tonu" value="{$load.carrier_tonu}" /></td>
		<td class="right faux_edit" id="carrier_tonu_extended"></td>
		</tr><tr>
		
		<td>Unload/Load</td>
		<td><input type="text" id="carrier_unload_load_amount" name="carrier_unload_load_amount" value="{$load.carrier_unload_load_amount}" /></td>
		<td><input type="text" id="carrier_unload_load" name="carrier_unload_load" value="{$load.carrier_unload_load}" /></td>
		<td class="right faux_edit" id="carrier_unload_load_extended"></td>
		</tr><tr>
		
		<td>Fuel</td>
		<td><input type="text" id="carrier_fuel_amount" name="carrier_fuel_amount" value="{$load.carrier_fuel_amount}" /></td>
		<td><input type="text" id="carrier_fuel" name="carrier_fuel" value="{$load.carrier_fuel}" /></td>
		<td class="right faux_edit" id="carrier_fuel_extended"></td>
		</tr><tr>
		
		<td>Other</td>
		<td><input type="text" id="carrier_other_amount" name="carrier_other_amount" value="{$load.carrier_other_amount}" /></td>
		<td><input type="text" id="carrier_other" name="carrier_other" value="{$load.carrier_other}" /></td>
		<td class="right faux_edit" id="carrier_other_extended"></td>
		
		</tr></table>
		</tr></table>	
		<input type="button" onclick="refresh_close()" value="Close">
		<script type="text/javascript">
				function column_updated(o)
				{
					if(o.name == 'cust_line_haul_amount' || o.name == 'cust_line_haul')
					{
						update_ext('cust_line_haul_extended', 'cust_line_haul_amount', 'cust_line_haul');
					}else if(o.name == 'carrier_line_haul_amount' || o.name == 'carrier_line_haul')
					{
						update_ext('carrier_line_haul_extended', 'carrier_line_haul_amount', 'carrier_line_haul');
					}else if(o.name == 'cust_detention_amount' || o.name == 'cust_detention')
					{
						update_ext('cust_detention_extended', 'cust_detention_amount', 'cust_detention');
					}else if(o.name == 'carrier_detention_amount' || o.name == 'carrier_detention')
					{
						update_ext('carrier_detention_extended', 'carrier_detention_amount', 'carrier_detention');
					}else if(o.name == 'cust_tonu_amount' || o.name == 'cust_tonu')
					{
						update_ext('cust_tonu_extended', 'cust_tonu_amount', 'cust_tonu');
					}else if(o.name == 'carrier_tonu_amount' || o.name == 'carrier_tonu')
					{
						update_ext('carrier_tonu_extended', 'carrier_tonu_amount', 'carrier_tonu');
					}else if(o.name == 'cust_unload_load_amount' || o.name == 'cust_unload_load')
					{
						update_ext('cust_unload_load_extended', 'cust_unload_load_amount', 'cust_unload_load');
					}else if(o.name == 'carrier_unload_load_amount' || o.name == 'carrier_unload_load')
					{
						update_ext('carrier_unload_load_extended', 'carrier_unload_load_amount', 'carrier_unload_load');
					}
					else if(o.name == 'cust_fuel_amount' || o.name == 'cust_fuel')
					{
						update_ext('cust_fuel_extended', 'cust_fuel_amount', 'cust_fuel');
					}else if(o.name == 'carrier_fuel_amount' || o.name == 'carrier_fuel')
					{
						update_ext('carrier_fuel_extended', 'carrier_fuel_amount', 'carrier_fuel');
					}else if(o.name == 'cust_other_amount' || o.name == 'cust_other')
					{
						update_ext('cust_other_extended', 'cust_other_amount', 'cust_other');
					}else if(o.name == 'carrier_other_amount' || o.name == 'carrier_other')
					{
						update_ext('carrier_other_extended', 'carrier_other_amount', 'carrier_other');
					}
				}
				
				function update_ext(ext_name, amt_name, rate_name)
				{
					var ext = document.getElementById(ext_name);
					var rate = document.getElementsByName(rate_name)[0];
					var amount = document.getElementsByName(amt_name)[0];
					ext.innerHTML = '$' + amount.value * rate.value;
					
				}
				function refresh_close()
				{
					window.opener.update_money_module();
					window.close();
				}
				update_ext('cust_line_haul_extended', 'cust_line_haul_amount', 'cust_line_haul');
				update_ext('carrier_line_haul_extended', 'carrier_line_haul_amount', 'carrier_line_haul');
				update_ext('cust_detention_extended', 'cust_detention_amount', 'cust_detention');
				update_ext('carrier_detention_extended', 'carrier_detention_amount', 'carrier_detention');
				update_ext('cust_tonu_extended', 'cust_tonu_amount', 'cust_tonu');
				update_ext('carrier_tonu_extended', 'carrier_tonu_amount', 'carrier_tonu');
				update_ext('cust_unload_load_extended', 'cust_unload_load_amount', 'cust_unload_load');
				update_ext('carrier_unload_load_extended', 'carrier_unload_load_amount', 'carrier_unload_load');
				update_ext('cust_fuel_extended', 'cust_fuel_amount', 'cust_fuel');
				update_ext('carrier_fuel_extended', 'carrier_fuel_amount', 'carrier_fuel');
				update_ext('cust_other_extended', 'cust_other_amount', 'cust_other');
				update_ext('carrier_other_extended', 'carrier_other_amount', 'carrier_other');
			</script>