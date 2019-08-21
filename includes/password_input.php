<?php
require_once("html_input.php");
class password_input extends html_input{
	var $confirm;
	function __construct($name, $confirm){
		parent::__construct('password', $name);
		$this->confirm = true;
	}
}
?>