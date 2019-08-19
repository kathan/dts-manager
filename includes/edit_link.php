<?php
class edit_link
{
	var $id;
	var $primary_key;
	var $location;
	function edit_link($location, $id, $primary_key)
	{
		$this->location = $location;
		$this->id = $id;
		$this->primary_key = $primary_key;
	}
	
	function render()
	{
		return "<a href=\"$this->location?action=edit&$this->primary_key=$this->id&pk=$this->primary_key\">Edit</a>";
	}
}
?>