<?php

require_once "includes/global.php";
class calendar{
	var $year;
	var $month;
	var $day;
	var $prev_year;
	var $prev_month;
	var $prev_day;
	var $next_year;
	var $next_month;
	var $next_day;
	var $attribs = [];
	var $months = array("", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec");
	var $days = array("", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
	function __construct($year=null, $month=null, $day=null){
		$this->set_year($year);
		$this->set_month($month);
		$this->set_day($day);
		$this->set_prev_date();
		$this->set_next_date();
	}
	
	function add_attrib($key, $value){
		$this->attribs[$key] = $value;
	}
	
	function get_link_attribs(){
		$keys = array_keys($this->attribs);
		foreach($keys as $key){
			$code .= "&$key=".$this->attribs[$key];
		}
		return $code;
	}
	
	function get_form_attribs(){
		$keys = array_keys($this->attribs);
		$code='';
		foreach($keys as $key){
			$code .= "<input type='hidden' name='$key' value='".$this->attribs[$key]."'>";
		}
		return $code;
	}
	
	function set_prev_date(){
		if($this->month == 1){
			$this->prev_month = 12;
			$this->prev_year = $this->year - 1;
		}else{
			$this->prev_month = $this->month - 1;
			$this->prev_year = $this->year;
		}
		$prev_date_str = $this->get_date_str() . " -1 day";
		$prev_date = strtotime($prev_date_str);
		$this->prev_day = date("d", $prev_date);
	}
	
	function get_date_str($delimiter=' '){
		return $this->day . $delimiter . $this->month . $delimiter . $this->year;
	}
	
	function set_next_date(){
		if($this->month == 12){
			$this->next_month = 1;
			$this->next_year = $this->year+1;
		}else{
			$this->next_month = $this->month+1;
			$this->next_year = $this->year;
		}
		$next_date_str = $this->get_date_str() . " +1 day";
		$next_date = strtotime($next_date_str);
		$this->next_day = date("d", $next_date);
	}
	
	function set_day($day=null){
		if(isset($day)){
			$this->day = $_REQUEST['day'];
		}
		elseif(isset($_REQUEST['day'])){
			$this->day = $_REQUEST['day'];
		}else{
			$this->day = date("j");
		}
	}
	
	function set_month($month=null){
		if(isset($month)){
			$this->month = $month;
		}
		elseif(isset($_REQUEST['month'])){
			$this->month = $_REQUEST['month'];
		}else{
			$this->month = date("n");
		}
		
		
	}
	
	function set_year($year=null){
		if(isset($year)){
			$this->year = $year;
		}
		elseif(isset($_REQUEST['year'])){
			$this->year = $_REQUEST['year'];
		}else{
			$this->year = date("Y");
		}
	}
	
	function get_days_in_month(){
		$first_day = $this->get_first_day();
		
		return date("t", $first_day);
	}
	
	function get_first_day(){
		$first_day_str = "1 " . $this->months[intval($this->month)] . " $this->year";
		return strtotime($first_day_str);
	}
	function get_navigator(){
		$code = $this->months[intval($this->month)]." $this->day, $this->year<br>
	        				Jump To:
								<form method=get action='$_SERVER[SCRIPT_NAME]'>"
								.$this->get_form_attribs()
								.$this->day_select()
								.$this->month_select()
								.$this->year_select()."
								<input type='submit' value='Go'>
								</form>";
		$code .= "
					<script>
						function set_days(){
							var days_of_month   = Array( 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
							var days_of_month_LY = Array( 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
							var year = document.getElementById('year');
							var month = document.getElementById('month');
							var day = document.getElementById('day');
							var cur_date = new Date()
							cur_date.setMonth(month.value);
							cur_date.setFullYear(year.value);
							if (leap_year(year.value)){
								days_of_month = days_of_month_LY;
							}
												
							var d_o_m = days_of_month[month.value-1];
							
							//==== revove all options ====
							var selected = day.selectedIndex+1;
							//alert(selected);
							while(day.options.length > 0){
								day.remove(0);
							}
							
							for(var i=1; i <= d_o_m;i++){
								var new_opt = document.createElement('option');
								new_opt.value = i;
								new_opt.text = i;
								if(i == selected){
									new_opt.selected = true;
								}
								try
    							{
    								day.add(new_opt,null); // standards compliant
    							}catch(ex){
    								day.add(new_opt); // IE only
    							}
							}
							//day.options = options;
							
						}
						function leap_year(year){
							if ((year/4)   != Math.floor(year/4))   return false;
							if ((year/100) != Math.floor(year/100)) return true;
							if ((year/400) != Math.floor(year/400)) return false;
							return true;
						}
					</script>";
		return $code;
	}
	function render($month=null, $year=null, $day=null){
		$content =  "
	        
	        	<table class='calendar'>
	        		<tr>
	        			<th class='prev_month'>
	        				<a href='http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]?month=$this->prev_month&year=$this->prev_year".$this->get_link_attribs()."'><<</a>
	        			</th>
	        			<th colspan='5' class='month'>
	        				".$this->get_navigator()."
	        			</th>
	        			<th class='next_month'>
	        				<a href='http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]?month=$this->next_month&year=$this->next_year".$this->get_link_attribs()."'>>></a>
	        			</th>
	        		</tr>
	        		<tr>";
		$month_start = date("w", $this->get_first_day())+1;

		$days_in_month = $this->get_days_in_month();
		
	    //=====Days of the week=====
		for($c = 1; $c <= 7; $c++){
			$content .= "
						<th class='day_of_week'>
	                		".$this->days[$c]."
	                	</th>";
		}
		//==========================
		
		$weeks_in_month = ceil(($days_in_month+($month_start-1))/7);
		
		for($r = 1; $r <= $weeks_in_month; $r++){
			$content .=  "
						<tr>";
			for($c = 1; $c <= 7; $c++){
				if ($r == 1 && $c == $month_start){
					$day = 1;
				}
				
				$content .=  "
							<td>";
				
	
				if ($day > 0 && $day <= $days_in_month){
						$content .=  "
								<a href='http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]?day=$day&month=$this->month&year=$this->year".$this->get_link_attribs()."'>
								<div style='border:1px solid black' class='day' id='$day-$month-$year' onclick=\"day_selected('$day', '$month', '$year')\">
									$day
								</div>
								</a>";
					$day++;
				}
				$content .=  "
							</td>";
			}
			$content .=  "
						</tr>";
		}
		$content .=  "
					</table>";
		
		return $content;
	}
	
	function day_select(){
		
		
		$code = "<select name='day' id='day'>";
		
		for($i=1; $i <= $this->get_days_in_month(); $i++){
			if ($this->day == $i){
				$code .="<option value='$i' selected>$i</option>";
			}else{
				$code .="<option value='$i'>$i</option>";
			}
		}
		$code .= "</select>";
		
		return $code;
	}
	
	function month_select(){
		$code = "<select name='month' id='month' onchange='set_days();'>";
		
		for($i=1; $i <= count($this->months)-1; $i++){
			if ($this->month == $i){
				$code .="<option value='$i' selected>".$this->months[$i]."</option>";
			}else{
				$code .="<option value='$i'>".$this->months[$i]."</option>";
			}
		}
		$code .= "</select>";
		return $code;
	}

	function year_select(){
		
		$year_list = [];
		for($y = $this->year - 2; $y <= $this->year + 2; $y++){
			$year_list[] = $y;
		}
	
		$code = "<select name='year' id='year' onchange='set_days();'>";
		foreach($year_list as $year){
			if ($this->year == $year){
				$code .="<option value='$year' selected>$year</option>";
			}else{
				$code .="<option value=$year>$year</option>";
			}
		}
		$code .= "</select>";
		return $code;
	}
}
?>