<?php
require_once("html_input.php");
class textarea_input extends html_input{
	var $cols;
	var $rows;
	function __construct($name, $value=''){
		parent::__construct('textarea', $name, $value);
		$this->set_cols(20);
		$this->set_rows(2);
	}
	
	function set_cols($cols){
		$this->cols = $cols;
	}
	
	function set_rows($rows){
		$this->rows = $rows;
	}
	
	function render(){
		$code = "<textarea name=\"$this->name\" id=\"$this->id\" cols=\"$this->cols\" rows=\"$this->rows\"";
		$keys = array_keys($this->custom_attributes);
		foreach($keys as $name){
			$code .= " $name='".addslashes($this->custom_attributes[$name])."'";
		}
		$code .= " >$this->value</textarea>";
		return $code;
	}
}
?>