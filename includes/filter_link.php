<?php
class filter_link
{
	var $id;
	var $foreign_key;
	var $location;
	function filter_link($location, $id, $foreign_key){
		$this->location = $location;
		$this->id = $id;
		$this->foreign_key = $foreign_key;
	}
	
	function render(){
		return "<a href=\"$this->location?action=filter&$this->foreign_key=$this->id&pk=$this->foreign_key\">Edit</a>";
	}
}
?>