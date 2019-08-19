<?php
require_once("html_input.php");

class radio_input extends html_input
{
	var $checked;
	function __construct($name, $value, $checked=false)
	{
		$this->checked = $checked;
		$this->html_input('radio', $name, $value);
		$this->set_label(ucwords($value));
	}
	
	function render()
	{
		//echo "boo";
		$code = $this->get_label()."<input type=\"$this->type\" name=\"$this->name\" ";
		if ($this->checked)
		{
			$code .= "checked";
		}
		$code .= "><br>\n";

		return $code;
	}
	
	
}
?>