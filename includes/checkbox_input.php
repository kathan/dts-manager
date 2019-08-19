<?php
require_once("html_input.php");
class checkbox_input extends html_input{
	function __construct($name, $value=''){
		parent::__construct('checkbox', $name, $value);
	}
	
	function render(){
		//return binaryToCB($this->name, $this->value);
		//$code = "<input type=\"checkbox\" name=\"$this->name\" id=\"$this->id\" value=\"\"";
		$code = "<input type=\"checkbox\" name=\"$this->name\" id=\"$this->id\"";
		$keys = array_keys($this->custom_attributes);
		foreach($keys as $name)
		{
			$code .= " $name='".addslashes($this->custom_attributes[$name])."'";
		}
		if (strval($this->value) == 1)
		{
			$code .= " checked";
		}
		$code .= ">";
		
		return $code;
	}
}
?>