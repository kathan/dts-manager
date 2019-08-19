<html>
	<head>
		<title>Carrier List</title>
	<script src="jquery.js" type="text/javascript"></script>
	<style type="text/css" media="print">
	{literal}
    /*.page
    {
     -webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);
     filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }*/
    
</style>

	<style type="text/css" media="all">
	
	.list_data
	{
		font-weight:normal;
		padding:3px;
	}
	{/literal}
	</style>
	</head>
<body class="page">
<!-- carrier_print_module -->
<div class="center">
	<h2>Carrier List</h2>
</div>
<table class="view list scrollTable" id="_portal" width="100%" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th>Id</th>
			<th>Name</th>
			<th>Address</th>
			<th>City</th>
			<th>State</th>
			<th>Phone</th>
			<th>Fax</th>
			<th>Contact</th>
		</tr>
	</thead>
	
		{section name=i loop=$carrier}
		<tbody class="{cycle values="normalRow,altRow"}">
		<tr id="_" >
			<td class="list_data carrier_id">{$carrier[i].id}</td>
			<td class="list_data name">{$carrier[i].name}</td>
			<td class="list_data phys_address">{$carrier[i].phys_address}</td>
			<td class="list_data phys_city">{$carrier[i].phys_city}</td>
			<td class="list_data phys_state">{$carrier[i].phys_state}</td>
			<td class="list_data phone">{$carrier[i].main_phone_number}</td>
			<td class="list_data fax">{$carrier[i].fax}</td>
			<td class="list_data contact_name">{$carrier[i].contact_name}</td>
		</tr>
		<tr>
			<td class="list_data">
				Notes:
			</td>
			<td colspan="7">
			</td>
		</tr>
		</tbody>
		{/section}
	
</table>
<script type="text/javascript">
{literal}
$(document).ready(function(e){
	window.print();
});
{/literal}
</script>
<!-- carrier_print_module -->
</body>
</html>