<?php

class menu_item{
	var $link;
	var $name;
	var $label;
	var $class;
	
	function __construct($link, $label){
		$this->link = $link;
		$this->name = $link;
		$this->label = $label;
	}
	
	function render(){
		return "<a href='$this->link' class='$this->class'><span>$this->label</span></a>";
	}
	
	function set_class($class){
		$this->class = $class;
	}
}
?>
