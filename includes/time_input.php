<?php

require_once("html_input.php");
class time_input extends text_input{
	function __construct($name, $value=""){
		parent::__construct($name, $value);
	}
	
	function render(){
		if($this->id == ''){
			$this->id = $this->name;
		}
		$code = "<INPUT TYPE=\"$this->type\" id=\"$this->id\" name=\"$this->name\" value=\"".htmlentities($this->value)."\" SIZE=10  readOnly=true datechange=\"function(y,m,d){db_save(this.id, this.value);};\"";
		$keys = array_keys($this->custom_attributes);
		foreach($keys as $name){
			$code .= " $name=\"".$this->custom_attributes[$name]."\"";
		}
		$code .= ">
				<img onClick=\"cal_$this->name.select(document.getElementById('$this->id'),'cal_button_$this->name','MM/dd/yyyy')\" ID=\"cal_button_$this->name\" src=\"images/cal.gif\" style='vertical-align:middle'>
				<span ID=\"cal_div_$this->name\" style='background-color:white;position:absolute'></span>";
		
		$code .= "
		
				<script>
					include('./CalendarPopup.js');
					var cal_$this->name = new CalendarPopup('cal_div_$this->name');
					//cal_$this->name.setReturnFunction('datechange');
				</script>
				";
	
		return $code;
	}
}
?>