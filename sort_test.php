<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	   "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>sorttable: Make all your tables sortable</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">
		<script src="sortable.js"></script>
		<style type='text/css'>
div.tableContainer {
	height: 295px; 	/* must be greater than tbody*/
	overflow: auto;
	margin: 0 auto;
	}

table.list {
	width: 99%;		/*100% of container produces horiz. scroll in Mozilla*/
	border: none;
	background-color: #f7f7f7;
	}
	
table.list>tbody	{  /* child selector syntax which IE6 and older do not support*/
	overflow: auto; 
	height: 150px;
	overflow-x: hidden;
	}
	
table.list>thead table.list>tr	{
	position:relative; 
	top: expression(offsetParent.scrollTop); /*IE5+ only*/
	}
	
table.list>thead .list>td, table.list>thead table.list>th {
	text-align: center;
	font-size: 14px; 
	background-color: oldlace;
	color: steelblue;
	font-weight: bold;
	border-top: solid 1px #d8d8d8;
	}	
	
table.list>td	{
	color: #000;
	padding-right: 2px;
	font-size: 12px;
	text-align: right;
	border-bottom: solid 1px #d8d8d8;
	border-left: solid 1px #d8d8d8;
	}

table.list>tfoot table.list>td	{
	text-align: center;
	font-size: 11px;
	font-weight: bold;
	background-color: papayawhip;
	color: steelblue;
	border-top: solid 2px slategray;
	}

table.list>td:last-child {padding-right: 20px;} /*prevent Mozilla scrollbar from hiding cell content*/

</style>
<style type='text/css' media='print'>
div.tableContainer {overflow: visible;	}
table>tbody	{overflow: visible; }
td {height: 14pt;} /*adds control for test purposes*/
thead td	{font-size: 11pt;	}
tfoot td	{
	text-align: center;
	font-size: 9pt;
	border-bottom: solid 1px slategray;
	}
	
thead	{display: table-header-group;	}
tfoot	{display: table-footer-group;	}
thead th, thead td	{position: static; } 


		</style>
	</head>
	<body>	
<table class='sortable' id='loads'>
	<tr>
		<td>load_id</td>
		<td>rating</td>
		<td>cancelled</td>
		<td>problem</td>
		<td>solution</td>
		<td>ordered</td>
		<td>booked</td>
		<td>checked_in</td>
		<td>loaded</td>
		<td>delivered</td>
		<td>customer_id</td>
		<td>customer_total</td>
		<td>carrier_total</td>
		<td>trailer_type</td>
		<td>pallets</td>
		<td>length</td>
		<td>size</td>
		<td>weight</td>
		<td>class</td>
		<td>carrier_id</td>
		<td>bounce</td>
		<td>order_by</td>
	</tr>
	<tr >
		<td >
			2<br />

		</td>
		<td >
			
		</td>
		<td >
			No
		</td>
		<td >
			
		</td>
		<td >

			
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>

		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			Kathan Inc
		</td>
		<td >

			0.00
		</td>
		<td >
			0.00
		</td>
		<td >
			
		</td>
		<td >
			0
		</td>

		<td >
			0
		</td>
		<td >
			0
		</td>
		<td >
			0.00
		</td>
		<td >

			
		</td>
		<td >
			Darrel Inc
		</td>
		<td >
			Yes
		</td>
		<td >
			joe
		</td><td ><a href="/dts/index.php?action=Edit&load_id=2">Edit</a><br /></td>

	</tr>
	<tr >
		<td >
			3<br />
		</td>
		<td >
			
		</td>
		<td >

			No
		</td>
		<td >
			
		</td>
		<td >
			
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >

			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>

		<td >
			Kathan Inc
		</td>
		<td >
			0.00
		</td>
		<td >
			0.00
		</td>
		<td >

			
		</td>
		<td >
			0
		</td>
		<td >
			0
		</td>
		<td >
			0
		</td>

		<td >
			0.00
		</td>
		<td >
			
		</td>
		<td >
			Darrel Inc
		</td>
		<td >
			Yes
		</td>

		<td >
			danny
		</td><td ><a href="/dts/index.php?action=Edit&load_id=3">Edit</a><br /></td>
	</tr>
	<tr >
		<td >
			4<br />
		</td>

		<td >
			
		</td>
		<td >
			No
		</td>
		<td >
			
		</td>
		<td >
			
		</td>

		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >

			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			Kathan Inc
		</td>
		<td >
			0.00
		</td>

		<td >
			0.00
		</td>
		<td >
			
		</td>
		<td >
			0
		</td>
		<td >
			0
		</td>

		<td >
			0
		</td>
		<td >
			0.00
		</td>
		<td >
			
		</td>
		<td >
			Darrel Inc
		</td>

		<td >
			Yes
		</td>
		<td >
			joe
		</td><td ><a href="/dts/index.php?action=Edit&load_id=4">Edit</a><br /></td>
	</tr>
	<tr >
		<td >

			5<br />
		</td>
		<td >
			
		</td>
		<td >
			No
		</td>
		<td >
			
		</td>

		<td >
			
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>

		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			Kathan Inc
		</td>
		<td >

			0.00
		</td>
		<td >
			0.00
		</td>
		<td >
			
		</td>
		<td >
			0
		</td>

		<td >
			0
		</td>
		<td >
			0
		</td>
		<td >
			0.00
		</td>
		<td >

			
		</td>
		<td >
			Darrel Inc
		</td>
		<td >
			Yes
		</td>
		<td >
			danny
		</td><td ><a href="/dts/index.php?action=Edit&load_id=5">Edit</a><br /></td>

	</tr>
	<tr >
		<td >
			6<br />
		</td>
		<td >
			
		</td>
		<td >

			No
		</td>
		<td >
			
		</td>
		<td >
			
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >

			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>

		<td >
			Kathan Inc
		</td>
		<td >
			0.00
		</td>
		<td >
			0.00
		</td>
		<td >

			
		</td>
		<td >
			0
		</td>
		<td >
			0
		</td>
		<td >
			0
		</td>

		<td >
			0.00
		</td>
		<td >
			
		</td>
		<td >
			Darrel Inc
		</td>
		<td >
			Yes
		</td>

		<td >
			joe
		</td><td ><a href="/dts/index.php?action=Edit&load_id=6">Edit</a><br /></td>
	</tr>
	<tr >
		<td >
			7<br />
		</td>

		<td >
			
		</td>
		<td >
			No
		</td>
		<td >
			
		</td>
		<td >
			
		</td>

		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >

			0000-00-00 00:00:00
		</td>
		<td >
			0000-00-00 00:00:00
		</td>
		<td >
			Kathan Inc
		</td>
		<td >
			0.00
		</td>

		<td >
			0.00
		</td>
		<td >
			
		</td>
		<td >
			0
		</td>
		<td >
			0
		</td>

		<td >
			0
		</td>
		<td >
			0.00
		</td>
		<td >
			
		</td>
		<td >
			Darrel Inc
		</td>

		<td >
			Yes
		</td>
		<td >
			danny
		</td>
		<td >
			<a href="/dts/index.php?action=Edit&load_id=7">Edit</a><br />
		</td>
	</tr>
</table>
	</body>
</html>