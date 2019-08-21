<?php
require_once("html_input.php");
class text_view extends html_input{
	function __construct($name, $value=""){
		parent::__construct('text', $name, $value);
	}
	
	function render(){
		$code = htmlentities($this->value);
		return $code;
	}
}
?>