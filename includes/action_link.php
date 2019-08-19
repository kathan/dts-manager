<?php
class action_link{
	var $id;
	var $primary_key;
	var $location;
	var $action;
	var $params = [];
	
	function action_link($location, $action, $id, $primary_key){
		$this->location = $location;
		$this->id = $id;
		$this->primary_key = $primary_key;
		$this->action = $action;
	}
	
	function render(){
		return "<a href=\"$this->location?action=$this->action&$this->primary_key=$this->id\">$this->action</a>";
	}
	
	function add_param($key, $value){
		$this->params[$key] = $value;
	}
}
?>