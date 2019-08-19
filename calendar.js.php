<html>
<head>
<style>
.cal_day:hover
{
	color:white;
}
</style>
</head>
<body>
<script>

get_month();
function day_selected(day, month, year)
{
	window.alert(month+'/'+day+'/'+year);
}

function get_month(month, year)
{
	
	var cur_date = new Date();
	if(!month)
	{
		var month = cur_date.getMonth()+1;
	}
	
	if(!year)
	{
		var year = cur_date.getFullYear();
	}
	var months = new Array("","Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec");
	var days = new Array("","Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
	if(month == 1)
	{
		var prev_month = 12;
		var prev_year = year-1;
	}else{
		var prev_month = month-1;
		var prev_year = year;
	}
	if(month == 12)
	{
		var next_month = 1;
		var next_year = year+1;
	}else{
		var next_month = month+1;
		var next_year = year;
	}
	if(document.getElementById('cal_tab'))
	{
		document.body.removeChild(document.getElementById('cal_tab'));
	}
	var cal_tab = document.createElement('table');
	document.body.appendChild(cal_tab);
	cal_tab.id = 'cal_tab';
	
	var cal_head = document.createElement('thead');
	cal_tab.appendChild(cal_head);
	
	var cal_head_row = document.createElement('tr');
	cal_head.appendChild(cal_head_row);
	
	var cal_prev = document.createElement('th');
	cal_head_row.appendChild(cal_prev);
	cal_prev.style.cursor = 'pointer';
	cal_prev.innerHTML ='&lt;&lt;';
	cal_prev.onclick = function() { get_month(prev_month, prev_year); };
	
	var cal_head = document.createElement('th');
	cal_head.colSpan = 5;
	cal_head_row.appendChild(cal_head);
	cal_head.innerHTML = months[month] + ' ' + year;
	
	var cal_next = document.createElement('th');
	cal_head_row.appendChild(cal_next);
	cal_next.style.cursor = 'pointer';
	cal_next.innerHTML ='&gt;&gt;';
	cal_next.onclick = function() { get_month(next_month, next_year); };
	
	var first_day_date = new Date();
	first_day_date.setDate(1);
	first_day_date.setMonth(month-1);
	first_day_date.setFullYear(year);
	var month_start = first_day_date.getDay()+1;

	var d_i_m_date = new Date();
	d_i_m_date.setDate(1);
	d_i_m_date.setMonth(month);
	d_i_m_date.setFullYear(year);
	d_i_m_date.setDate(d_i_m_date.getDate()-1);
	var days_in_month = d_i_m_date.getDate();
	
	var cal_body = document.createElement('tbody');
	cal_tab.appendChild(cal_body);
	
	var cal_row = document.createElement('tr');
	cal_body.appendChild(cal_row);
    //=====Days of the week=====
	for(c = 1; c <= 7; c++)
	{
		//d_o_w is day of week
		var d_o_w = document.createElement('th');
		cal_row.appendChild(d_o_w);
		d_o_w.innerHTML = days[c];
	}
	//==========================
	
	weeks_in_month = Math.ceil((days_in_month+(month_start-1))/7);
	var d_o_m = 0;
	for(r = 1; r <= weeks_in_month; r++)
	{
		var cal_row = document.createElement('tr');
		cal_body.appendChild(cal_row);
		
		for(c = 1; c <= 7; c++)
		{
			if (r == 1 && c == month_start)
			{
				var d_o_m = 1;
			}
			var cal_day = document.createElement('td');
			cal_row.appendChild(cal_day);
			
			if (d_o_m > 0 && d_o_m <= days_in_month)
			{
				var cal_day_div = document.createElement('div');
				cal_day.appendChild(cal_day_div);
				cal_day.style.cursor = 'pointer';
				cal_day.classname = 'cal_day';
				cal_day_div.innerHTML = d_o_m;
				cal_day_div.day = d_o_m;
				cal_day_div.month = month;
				cal_day_div.year = year;
				
				cal_day_div.onclick = function() { day_selected(d_o_m, month, year); };
				d_o_m++;
			}
			
		}
		
	}	
}
</script>

</body>
</html>
<?php
?>