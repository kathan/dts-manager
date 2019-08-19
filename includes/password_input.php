<?php
require_once("html_input.php");
class password_input extends html_input{
	var $confirm;
	function __construct($name, $confirm){
		$this->html_input('password', $name);
		$this->confirm = true;
	}
}
?>