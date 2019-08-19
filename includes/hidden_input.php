<?php
require_once("html_input.php");
class hidden_input extends html_input
{
	function __construct($name, $value='')
	{
		parent::__construct('hidden', $name, $value);
		$this->set_label('');
	}
}
?>