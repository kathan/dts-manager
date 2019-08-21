<?php
require_once("html_input.php");
require_once("radio_input.php");

class radio_group extends html_input{
	var $inputs = [];
	
	function __construct($name){
		parent::__construct($name);
		$this->name = $name;
		$this->label = ucwords(str_replace('_',' ',$name));
	}
	
	function render(){
	
		//echo count($this->inputs)."<BR>";
		$code = "<fieldset>\n<legend>$this->label</legend>";
		
		//for($i=0; $i < count($this->inputs); $i++)
		foreach($this->inputs as $input){
			//$code .= $this->inputs[$i]->render();
			$code .= $input->render();
		}
		$code .= "</fieldset>\n";
		return $code;
	}
	
	function &add_radio_input($value, $checked=false){
		$new_radio_input = new radio_input($this->name, $value, $checked);
		
		//array_push($this->inputs, $new_radio_input);
		$this->inputs[$value] =& $new_radio_input;
		//echo count($this->inputs)."<BR>";
		return $new_radio_input;
	}
	
	
	
	/*function get_label(){
		return $this->label;
	}*/
}
?>