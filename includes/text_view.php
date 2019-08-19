<?php
require_once("html_input.php");
class text_view extends html_input
{
	function text_view($name, $value="")
	{
		$this->html_input('text', $name, $value);
	}
	
	function render()
	{
		$code = htmlentities($this->value);
		return $code;
	}
}
?>