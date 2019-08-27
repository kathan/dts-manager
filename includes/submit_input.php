<?php
require_once("html_input.php");
class submit_input extends html_input{
	function __construct($value, $name=""){
		parent::__construct('submit', $name, $value);
	}
	
	function get_label(){
		return "";
	}
}
?>