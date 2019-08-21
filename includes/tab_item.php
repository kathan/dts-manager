<?php
require_once"menu_item.php";
class tab_item extends menu_item{
	
	function __construct($link, $label){
		parent::__construct($link,$label);
	}
	
	function render(){
		return "<li><a href='$this->link' class='$this->class'><span>$this->label</span></a></li>";
	}
}
?>