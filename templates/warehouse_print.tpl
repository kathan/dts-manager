<script src="jquery.js" type="text/javascript"></script>
	<style type="text/css" media="print">
	{literal}
	table { page-break-inside:avoid; }
    tr    { page-break-inside:avoid;}
    tbody { page-break-inside:avoid;page-break-after:auto; }
    thead { display:table-header-group; }
	.notes{page-break-after:auto; }
    /*body
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

<!-- warehouse_print_module -->
<div class="center">
	<h2>Warehouse List</h2>
	
</div>
<table class="view list scrollTable" id="_portal" width="100%" cellpadding="0" cellspacing="0">
	<thead style="display: table-header-group;">
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
	
	{section name=i loop=$warehouse}
	<tbody class="{cycle values="normalRow,altRow"}">
		<tr id="_">
			<td class="list_data warehouse_id">{$warehouse[i].id}</td>
			<td class="list_data name">{$warehouse[i].name}</td>
			<td class="list_data address">{$warehouse[i].address}</td>
			<td class="list_data city">{$warehouse[i].city}</td>
			<td class="list_data state">{$warehouse[i].state}</td>
			<td class="list_data phone">{$warehouse[i].phone}</td>
			<td class="list_data fax">{$warehouse[i].fax}</td>
			<td class="list_data contact_name">{$warehouse[i].contact_name}</td>
		</tr>
		<tr class="notes">
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
<!-- warehouse_print_module -->