<?php
require_once("html_input.php");
class text_input extends html_input{
	function __construct($name, $value=""){
		parent::__construct('text', $name, $value);
	}
}
?>