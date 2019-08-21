<?php
require_once("html_input.php");
class cancel_input extends html_input{
	function __construct($value, $name=""){
		parent::__construct('cancel', $name, $value);
	}
}
?>