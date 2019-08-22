<h2>{$prefix}{$cust.customer_id}</h2>
	<form method="post">
		<input type="hidden" name="action" value="{$cust_to_wh}" />
		<input type="hidden" name="page" value="warehouse" />
		<input type="hidden" name="customer_id" value="{$cust.customer_id}" />
		<input type="submit" value="Add As Warehouse" />
	</form>
		<!--==== Main ====-->
		<table width="100%">
			<tr>
				<td class="left" style="width:50%!important">
		<!--==== End Main ====-->
					<fieldset>
						<legend>Main</legend>
							<table>
	
		<!--==== Customer Name====-->
								<tr>
									<td>Customer ID:</td>
									<td>{$full_cust_id}</td>
								</tr>
								<tr>
									<td>Name:</td>
									<td><input type="text" name="name" id="action=update&table=customer&customer_id={$cust.customer_id}&name=" onchange="db_save(this);" value="{$cust.name}" /></td>
								</tr>
		<!--==== End Customer Name======-->
		
		<!--====Customer Address====-->
								<tr>
									<td>Address:</td>
									<td><input type="text" name="address" id="action=update&table=customer&customer_id={$cust.customer_id}&address=" onchange="db_save(this);" value="{$cust.address}" /></td>
								</tr>
		<!--===== End Customer Address ====-->
		
		<!--==== Customer City State Zip====-->
								<tr>
									<td>City/State/Zip:</td>
									<td><input type="text" name="city" id="action=update&table=customer&customer_id={$cust.customer_id}&city=" onchange="db_save(this);" value="{$cust.city}" /></td>
									<td><input type="text" name="state" id="action=update&table=customer&customer_id={$cust.customer_id}&state=" onchange="db_save(this);" value="{$cust.state}" size="2" /></td>
									<td><input type="text" name="zip" id="action=update&table=customer&customer_id={$cust.customer_id}&zip=" onchange="db_save(this);" value="{$cust.zip}" /></td>
								</tr>
		<!--====== End Customer City State Zip====== -->
		
		<!--====Phone Fax====-->
								<tr>
									<td>Phone/Fax:</td>
									<td><input type="text" name="phone" id="action=update&table=customer&customer_id={$cust.customer_id}&phone=" onchange="db_save(this);" value="{$cust.phone}" /></td>
									<td colspan=2><input type="text" name="fax" id="action=update&table=customer&customer_id={$cust.customer_id}&fax=" onchange="db_save(this);" value="{$cust.fax}" /></td>
								</tr>
		<!--=====================-->
		
		<!--====Contact Name====-->
								<tr>
									<td>Contact&nbsp;Name:</td>
									<td><input type="text" name="contact_name" id="action=update&table=customer&customer_id={$cust.customer_id}&contact_name=" onchange="db_save(this);" value="{$cust.contact_name}" /></td>
								</tr>
		<!--=====================-->
	
		<!--====Contact Email====-->
								<tr>
									<td>Email:</td>
									<td><input type="text" name="email" id="action=update&table=customer&customer_id={$cust.customer_id}&email=" onchange="db_save(this);" value="{$cust.email}" /></td>
								</tr>
		<!--=====================-->
		
							</table>
						</fieldset>
		<!--=====================-->
		<!--=====================-->
		
		<!--====End Main====-->
		
		
		<!--====Notes====-->
					<fieldset >
						<legend>Notes</legend>
						<form>
							<input type="button" value="New" onclick="popUp('?page=customer_notes&action=New&customer_id={$cust.customer_id}&sml_view')">
						</form>
						<div id="customer_notes">
						</div>

					</fieldset>
					<style>
						#customer_notes .tableContainer{ldelim}height:99% !important;
						{rdelim}
						
						#customer_notes table>tbody{ldelim}height:10em !important;{rdelim}
					</style>
		<!--=============-->
				</td>
				<td valign="top" style="width:50%!important">
		<!--====Billing====-->
		<!--===============-->
				<fieldset>
					<legend>Billing</legend>
					<table>
		{if $cust.billing_address == "" && $cust.billing_city == "" && $cust.billing_state == "" && $cust.billing_zip == "" && $cust.billing_phone == "" && $cust.billing_fax == "" && $cust.billing_contact_name == ""}
						<tr>
							<td>Same as main:<input type=checkbox onchange="same_address(this)"></td>
						</tr>
		{/if}
		<!--====Billing Name====-->
		<tr>
			<td>Attention:</td>
			<td><input type="text" name="billing_attention" id="action=update&table=customer&customer_id={$cust.customer_id}&billing_attention=" onchange="db_save(this);" value="{$cust.billing_attention}" /></td>
		</tr>
		<!--=====================-->
		
		<!--====Billing Address====-->
		<tr>
			<td>Address:</td>
			<td><input type="text" name="billing_address" id="action=update&table=customer&customer_id={$cust.customer_id}&billing_address=" onchange="db_save(this);" value="{$cust.billing_address}" /></td>
		</tr>
		<!--=====================-->
		
		<!--====Billing City State Zip====-->
		<tr>
			<td>City/State/Zip:</td>
			<td><input type="text" name="billing_city" id="action=update&table=customer&customer_id={$cust.customer_id}&billing_city=" onchange="db_save(this);" value="{$cust.billing_city}" /></td>
			<td><input type="text" name="billing_state" id="action=update&table=customer&customer_id={$cust.customer_id}&billing_state=" onchange="db_save(this);" value="{$cust.billing_state}" size="2"/></td>
			<td><input type="text" name="billing_zip" id="action=update&table=customer&customer_id={$cust.customer_id}&billing_zip=" onchange="db_save(this);" value="{$cust.billing_zip}" maxlength="10"/></td>
		</tr>
		<!--=====================-->
		
		<!--====Billing Fax====-->
		<tr>
			<td>Phone/Fax:</td>
			<td><input type="text" name="billing_phone" id="action=update&table=customer&customer_id={$cust.customer_id}&billing_phone=" onchange="db_save(this);" value="{$cust.billing_phone}" /></td>
			<td colspan=2><input type="text" name="billing_fax" id="action=update&table=customer&customer_id={$cust.customer_id}&billing_fax=" onchange="db_save(this);" value="{$cust.billing_fax}" /></td>
		</tr>
		<!--=====================-->
		
		<!--====Billing Name====-->
		<tr>
			<td>Contact&nbsp;Name:</td>
			<td><input type="text" name="billing_contact_name" id="action=update&table=customer&customer_id={$cust.customer_id}&billing_contact_name=" onchange="db_save(this);" value="{$cust.billing_contact_name}" /></td>
		</tr>
		<!--=====================-->

		</table>
	</fieldset>
		<!--=====================-->
		<!--=====================-->
		
		<!--====Misc====-->
	<fieldset>
		<table>
		
		{if $admin}
			<tr>
				<td>Account Status:</td>
				<td>{html_options name="account_status" options=$account_statuses selected=$cust.account_status onchange="db_save(this);" id="action=update&table=customer&customer_id=`$cust.customer_id`&account_status="}</td>
			</tr>
		{else}
			<tr>
				<td>Account Status:</td>
				<td class="faux_edit">{$cust.account_status}</td>
		{/if}
				<td>Account Owner:</td>
				<td>{html_options name="acct_owner" options=$acct_owners selected=$cust.acct_owner onchange="db_save(this);" id="action=update&table=customer&customer_id=`$cust.customer_id`&acct_owner="}</td>
			</tr>
		</table>
	</fieldset>
		<!--=============-->
		
		
		<!--====Loads====-->
	<fieldset>
		<legend>Loads</legend>
		<div id="load">
		</div>
	</fieldset>
		
	<style>
		#load .tableContainer{ldelim}height:12em !important;{rdelim}
		#load table>tbody{ldelim}height:9em !important;{rdelim}
	</style>
		<!--=============-->
	</td>
</tr>
</table>
		{if $smarty.get.action == 'New'}
			<input type="button" onclick="cancel();" value="Cancel" />
		{/if}
			<input type="button" onclick="window.location='?page={$smarty.get.page}'" value="Save" />
		
<script type="text/javascript">
	{literal}
	jQuery(document).ready(function(){
		get_portal("load", "");
		get_portal("customer_notes", "");
	});
			
			
	function cancel()
	{
		if(confirm("Are you sure you want to cancel?"))
		{
		{/literal}
			window.location="?action={$delete_str}&page={$smarty.get.page}&customer_id={$cust.customer_id}";
		{literal}
		}
	}
			
	function same_address(cb)
	{
		if(cb.checked)
		{
			var a = document.getElementsByName("billing_address");
			var b = document.getElementsByName("address");
			a[0].value = b[0].value;
			
			db_save(a[0]);
			
			var a = document.getElementsByName("billing_city");
			var b = document.getElementsByName("city");
			a[0].value = b[0].value;
			
			db_save(a[0]);
			
			var a = document.getElementsByName("billing_state");
			var b = document.getElementsByName("state");
			a[0].value = b[0].value;
			
			db_save(a[0]);
			
			var a = document.getElementsByName("billing_zip");
			var b = document.getElementsByName("zip");
			a[0].value = b[0].value;
			
			db_save(a[0]);
			
			var a = document.getElementsByName("billing_phone");
			var b = document.getElementsByName("phone");
			a[0].value = b[0].value;
			
			db_save(a[0]);
			
			var a = document.getElementsByName("billing_fax");
			var b = document.getElementsByName("fax");
			a[0].value = b[0].value;
			
			db_save(a[0]);
			
			var a = document.getElementsByName("billing_contact_name");
			var b = document.getElementsByName("contact_name");
			a[0].value = b[0].value;
			
			db_save(a[0]);
		}
	}
	{/literal}
</script>