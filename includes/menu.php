<?php
require_once("auth.php");

class menu{
	var $items=[];
	var $active;
	var $active_class;
	function __construct(){
		$this->active ='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	
	function add_item(&$item){
		$this->items[$item->name] =& $item;
	}
	
	function set_active($link){
		$this->active = $link;
	}
	
	function render(){
		
		if(array_key_exists($this->active, $this->items)){
			$active_item =& $this->items[$this->active];
			$active_item->class = $this->active_class;
		}
		
		$c = "
			<table class='menu'>
  				<tr>\n";
  	$names = array_keys($this->items);
  	foreach($names as $name){
  		$c .= "<td class='menu_item'>".$this->items[$name]->render()."</td>\n";
  	}
		$c .= "	
					<td>
				<table style='text-align:center;font-size:8pt;color:white;'>
				<tr>
					<td style='width:50%;background-color:green'>TL</td>
					<td style='width:50%;background-color:blue'>OCEAN</td>
				</tr>
				<tr>
					<td style='background-color:red'>LTL</td>
					<td style='background-color:brown'>PARTIAL</td>
				</tr>
				<tr>
					<td style='background-color:orange'>RAIL</td>
					<td style='background-color:purple'>REFRIG</td>
				</tr>
			</table>";
		$c .= "			</td>
				</tr>
			</table>\n";
		return $c;
	}
}

?>