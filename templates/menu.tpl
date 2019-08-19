{if $logged_in}
<script type="text/javascript">
	{literal}
	function make_new()
	{
		var p = document.getElementById("page");
					
		if(p.value)
		{
			p.form.submit();
		}
	}
</script>

<table class="menu">
  	<tr>
		<td class="menu_item">
			<a href="?page=load" class="">
				<img src="images/package.gif" border="0" width="40px" alt="" /><br />Loads
			</a>
		</td>
		<td class="menu_item">
			<a href="?page=customer" class="">
				<img src="images/accounts.gif" border="0" width="40px" alt="" /><br />Customers
			</a>
		</td>
		<td class="menu_item">
			<a href="?page=carrier" class=""><span><img src="images/truck.gif" border="0" width="40px" alt="" /><br />Carriers
			</a>
		</td>
		<td class="menu_item">
			<a href="?page=warehouse" class="">
				<img src="images/warehouse.gif" border="0" width="40px" alt="" /><br />Warehouses
			</a>
		</td>
		<td class="menu_item">
			<a href="?page=lanes" class="">
				<img src="images/lanes.gif" border="0" width="40px" alt="" /><br />Lanes
			</a>
		</td>
		<td class="menu_item">
			<a href="?page=users" class="">
				<img src="images/users.png" border="0" width="40px" alt="" /><br />Users
			</a>
		</td>
		<td class="menu_item">
			<a href="?page=reports" class="">
				<img src="images/reports.jpg" border="0" width="40px" alt="" /><br />Reports
			</a>
		</td>
		
		<td class="menu_item">
			<a href="?page=users&action=edit&user_id=1" class="">
				logged&nbsp;in&nbsp;as&nbsp;{$user}
			</a>
		</td>
		<td class="menu_item">
			<a href="?page=logout" class="">
				Log out
			</a>
		</td>
		<td class="menu_item">
			<form method="GET" action="/dts/index.php" name="" enctype="multipart/form-data">
				<input type="hidden" name="action" value="New">
				New:
				<select name="page" id="page"  onchange="make_new();">
					<option></option>
					<option value="load">Load</option>
					<option value="customer">Customer</option>
					<option value="carrier">Carrier</option>
					<option value="warehouse">Warehouse</option>
				</select>
			</form>
		</td>
		<td>
			<table style="text-align:center;font-size:8pt;color:white;">
				<tr>
					<td style="width:50%;background-color:green">TL</td>
					<td style="width:50%;background-color:blue">OCEAN</td>
				</tr>
				<tr>
					<td style="background-color:red">LTL</td>
					<td style="background-color:brown">PARTIAL</td>
				</tr>
				<tr>
					<td style="background-color:orange">RAIL</td>
					<td style="background-color:purple">REFRIG</td>
				</tr>
			</table>			
		</td>
	</tr>
</table>
{else}
{/if}