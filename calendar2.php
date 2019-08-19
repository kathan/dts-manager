
<head>
<script src='http://darrelkathan.com/dts/base.js'></script>
</head>
<body>
	<INPUT TYPE="text" VALUE="" SIZE=10 id='date_input_1' readOnly=true>
	<img onClick="cal.select(document.getElementById('date_input_1'),'cal_button','MM/dd/yyyy'); return false;" ID="cal_button" src='images/cal.gif' style='vertical-align:middle'><br>
	<INPUT TYPE="text" VALUE="" SIZE=10 id='date_input_2' readOnly=true>
	<img onClick="cal.select(document.getElementById('date_input_2'),'cal_button','MM/dd/yyyy'); return false;" ID="cal_button" src='images/cal.gif' style='vertical-align:middle'>
	<span ID="cal_div"></span>
	<script>
		include('./CalendarPopup.js');
		var cal = new CalendarPopup("cal_div");
	</script>
</body>
