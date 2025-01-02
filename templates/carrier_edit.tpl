<!-- start carrier edit -->
<script type="type/javascript" src="js/CalendarPopup.js"></script>
<h2>{$prefix}{$carrier.carrier_id}</h2>
<table width="100%">
	<tbody>
		<tr>
			<td valign="top">
				<fieldset>
					<legend>Contact</legend>
					<table border="0" width="100%">
						<tbody>
							<tr>
								<td>Carrier Name</td>
								<td><input name="name" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;name=" onchange="db_save(this);" maxlength="30" value="{$carrier.name}" type="text" /></td>
							</tr>
							<tr>
								<td>Contact Name</td>
								<td><input name="contact_name" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;contact_name=" onchange="db_save(this);" maxlength="30" value="{$carrier.contact_name}" type="text" /></td>
							</tr>
							<tr>
								<td>Physical Address</td>
								<td><input name="phys_address" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;phys_address=" onchange="db_save(this);" maxlength="100" value="{$carrier.phys_address}" type="text" /></td>
							</tr>
							<tr>
								<td>City State Zip</td>
								<td><input name="phys_city" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;phys_city=" onchange="db_save(this);" maxlength="100" value="{$carrier.phys_city}" type="text" /></td>
								<td><input name="phys_state" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;phys_state=" size="2" onchange="db_save(this);" maxlength="2" value="{$carrier.phys_state}" type="text" /></td>
								<td><input name="phys_zip" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;phys_zip=" size="10" onchange="db_save(this);" maxlength="10" value="{$carrier.phys_zip}" type="text" /></td>
							</tr>
							<tr>
								<td>Main Phone</td>
								<td><input name="main_phone_number" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;main_phone_number=" onchange="db_save(this);" maxlength="30" value="{$carrier.main_phone_number}" type="text" /></td>
							</tr>
							<tr>
								<td>Fax</td>
								<td><input name="fax" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;fax=" onchange="db_save(this);" maxlength="30" value="{$carrier.fax}" type="text"></td>
							</tr>
							<tr>
								<td>Carrier Rep</td>
								<td>
								{html_options options=$users name="carrier_rep" selected=$carrier.carrier_rep id="action=update&amp;table=carrier&amp;carrier_id=`$carrier.carrier_id`&amp;carrier_rep=" onchange="db_save(this);" value=$carrier.carrier_rep}
			
								</td>
							</tr>
						</tbody>
					</table>
				</fieldset>
				
				<fieldset>
					<legend>Accounts Receivable</legend>
						<table border="0" width="100%">
							<tbody>
								<tr>
									<td>Address</td>
									<td><input name="acct_rec_address" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;acct_rec_address=" onchange="db_save(this);" maxlength="100" value="{$carrier.acct_rec_address}" type="text" /></td>
								</tr>
								<tr>
									<td>City State Zip</td>
									<td><input name="acct_rec_city" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;acct_rec_city=" onchange="db_save(this);" maxlength="100" value="{$carrier.acct_rec_city}" type="text" /></td>
									<td><input name="acct_rec_state" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;acct_rec_state=" size="2" onchange="db_save(this);" maxlength="2" value="{$carrier.acct_rec_state}" type="text" /></td>
									<td><input name="acct_rec_zip" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;acct_rec_zip=" size="10" onchange="db_save(this);" maxlength="10" value="{$carrier.acct_rec_zip}" type="text" /></td>
								</tr>
							</tbody>
						</table>
					</fieldset>
					
					<fieldset>
						<legend>Notes</legend>
						<table border="0" width="100%">
							<tbody>
								<tr>
									<td>Notes</td>
									<td><textarea name="carrier_notes" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;carrier_notes=" cols="20" rows="2" onchange="db_save(this);">{$carrier.carrier_notes}</textarea></td>
								</tr>
							</tbody>
						</table>
					</fieldset>
				</td>
				<td valign="top">
					<fieldset>
						<legend>Certification</legend>
						<table border="0" width="100%">
							<tbody>
								<tr>
									<td class="label">Do Not Load</td>
									<td>
										{if $admin}
											{if $carrier.do_not_load}
												<input name="do_not_load" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;do_not_load=" onchange="db_save(this);" type="checkbox" checked="checked"/>
											{else}
												<input name="do_not_load" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;do_not_load=" onchange="db_save(this);" type="checkbox" />
											{/if}
										{else}
											{if $carrier.do_not_load}
												<span class="edit">Yes</span>
											{else}
												<span class="edit">No</span>
											{/if}
										{/if}
									</td>
								</tr>
								<tr>
									<td class="label">Insurance On File</td>
									<td>
									{if $admin}
										{if $carrier.insurance_on_file}
											<input name="insurance_on_file" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;insurance_on_file=" onchange="db_save(this);" checked="checked" type="checkbox" />
										{else}
											<input name="insurance_on_file" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;insurance_on_file=" onchange="db_save(this);" type="checkbox" />
										{/if}
									{else}
										{if $carrier.insurance_on_file}
											<span class="edit">Yes</span>
										{else}
											<span class="edit">No</span>
										{/if}
									{/if}
									</td>
									<td>
										<span class="label">Insurance Expires</span>
									</td>
									<td>
										<span class="edit">
											{if $admin}
											<input id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;insurance_expires=" name="insurance_expires" value="{$carrier.insurance_expires}" size="10" readonly="true" datechange="function(y,m,d){ldelim}db_save(this.id, this.value);{rdelim};" onchange="db_save(this);" type="text" />
											<img onclick="cal_insurance_expires.select(document.getElementById('action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;insurance_expires='),'cal_button_insurance_expires','MM/dd/yyyy')" id="cal_button_insurance_expires" src="images/cal.gif" style="vertical-align: middle;" />
											<span id="cal_div_insurance_expires" style="background-color: white; position: absolute;">
											
											</span>
											{else}
												<span class="label">{$carrier.insurance_expires}</span>
											{/if}
										</span>
									</td>
								</tr>
								<tr>
									<td class="label">Packet On File</td>
									<td>
									{if $admin}
										{if $carrier.packet_on_file}
											<input name="packet_on_file" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;packet_on_file=" onchange="db_save(this);" checked="checked" type="checkbox" />
										{else}
											<input name="packet_on_file" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;packet_on_file=" onchange="db_save(this);" type="checkbox" />
										{/if}
									{else}
										{if $carrier.packet_on_file}
											<span class="edit">Yes</span>
										{else}
											<span class="edit">No</span>
										{/if}
									{/if}
									</td>
								</tr>
								<tr>
									<td class="label">Certification Holder</td>
									<td>
									{if $admin}
										{if $carrier.certification_holder}
											<input name="certification_holder" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;certification_holder=" onchange="db_save(this);" checked="checked" type="checkbox" />
										{else}
											<input name="certification_holder" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;certification_holder=" onchange="db_save(this);" type="checkbox" />
										{/if}
									{else}
										{if $carrier.certification_holder}
											<span class="edit">Yes</span>
										{else}
											<span class="edit">No</span>
										{/if}
									{/if}
									</td>
								</tr>
								<tr>
									<td class="label">Limited Liabilty</td>
									<td>
									{if $admin}
										<input name="limited_liability" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;limited_liability=" onchange="db_save(this);" value="{$carrier.limited_liability}" type="text" />
									{else}
										<span class="edit">{$carrier.limited_liability}</span>
									{/if}
									</td>
								</tr>
								<tr>
									<td class="label">Cargo Limit</td>
									<td>
									{if $admin}
										<input name="cargo_limit" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;cargo_limit=" onchange="db_save(this);" value="{$carrier.cargo_limit}" type="text" />
									{else}
										<span class="edit">{$carrier.cargo_limit}</span>
									{/if}
									</td>
								</tr>
								<tr>
									<td class="label">MC Number</td>
									<td>
									{if $admin}
										<input name="mc_number" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;mc_number=" onchange="db_save(this);" maxlength="255" value="{$carrier.mc_number}" type="text" />
									{else}
										<span class="edit">{$carrier.mc_number}</span>
									{/if}
									</td>
								</tr>
								<tr>
									<td class="label">ICC Number</td>
									<td>
									{if $admin}
										<input name="icc_number" id="action=update&amp;table=carrier&amp;carrier_id={$carrier.carrier_id}&amp;icc_number=" onchange="db_save(this);" maxlength="255" value="{$carrier.icc_number}" type="text" />
									{else}
										<span class="edit">{$carrier.icc_number}</span>
									{/if}
									</td>
								</tr>
							</tbody>
						</table>
					</fieldset>
					<fieldset>
						<legend>Loads</legend>
						<div id="load">
							<div class="tableContainer" id="tableContainer" style="height: 295px; overflow: auto; margin: 0pt auto;">
								<table class="view list scrollTable" id="_portal" style="width: 99%; border: medium none; background-color: rgb(247, 247, 247);">
									<thead>
										<tr>
											<th>Origin</th>
											<th>Dest</th>
										</tr>
									</thead>
									<tbody style="overflow-y: auto; overflow-x: hidden;">
										{section name=i loop=$loads}
										<tr id="load_{$loads[i].load_id}" class="{cycle values="normalRow,altRow"}" onclick="row_clicked('{$loads[i].load_id}', 'load_id', 'load')">
											<td class="list_data origin" style="padding-right: 2px; text-align: left; font-weight: bold; font-size: 12px; color: black;">{$loads[i].origin}</td>
											<td class="list_data dest" style="padding-right: 2px; text-align: left; font-weight: bold; font-size: 12px; color: black;"><span style="color: red;">{$loads[i].dest}</span></td>
										</tr>
										{/section}
									</tbody>
								</table>
							</div>
						</div>
				</fieldset>
			</td>
		</tr>
	</tbody>
</table>
{if $smarty.get.action == 'New'}
	<input type="button" onclick="cancel();" value="Cancel" />
{/if}

<input type="button" onclick="window.location='?page={$smarty.get.page}'" value="Save" />
<style type="text/css">
	#load .tableContainer{ldelim}width:97%;height:7em !important;{rdelim}
	#load table>tbody{ldelim}height:3em !important;{rdelim}
</style>
<script type="text/javascript">
{literal}
	include('js/CalendarPopup.js');
	jQuery(document).ready(function(){
		get_portal('load');
		cal_insurance_expires = new CalendarPopup('cal_div_insurance_expires');
	});
	function cancel(){
		if(confirm("Are you sure you want to cancel?")){
		{/literal}
			window.location="?action={$delete_str}&page={$smarty.get.page}{if isset($cust.customer_id)}&customer_id={$cust.customer_id}{/if}";
		{literal}
		}
	}
	{/literal}
</script>
<!-- end carrier edit -->