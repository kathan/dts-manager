<?php
class html_input{
	var $type;
	var $name;
	var $value;
	var $label;
	var $size = 0;
	var $max_length;
	var $custom_attributes=[];
	var $id;
	
	function __construct($type, $name, $value=""){
		$this->type = $type;
		$this->name = $name;
		$this->label = $name;
		$this->value = $value;
	}
	
	function set_id($id){
		$this->id = $id;
	}
	
	function render(){
		$code = "<input type=\"$this->type\" name=\"$this->name\" id=\"$this->id\" ";
		if($this->size > 0){
			$code .= " size=\"$this->size\"";
		}
		
		$keys = array_keys($this->custom_attributes);
		foreach($keys as $name){
			$code .= " $name='".addslashes($this->custom_attributes[$name])."'";
		}
		
		if($this->max_length > 0){
			$code .= " maxlength=\"$this->max_length\"";
		}
		$code .= " value=\"".htmlentities($this->value)."\">\n";
		return $code;
	}
	
	function set_size($size){
		$this->size = $size;
	}
	
	function set_max_length($max_length){
		$this->max_length = $max_length;
	}
	
	function set_value($value){
		$this->value = $value;
	}
	
	function set_name($name){
		$this->name = $name;
	}
	
	function set_label($label){
		$this->label = $label;
	}
	
	function get_label(){
		if($this->label){
			return ucwords(str_replace('_',' ',$this->label)). ":";
		}
	}
	
	function get_name(){
		return $this->name;
	}
	
	function add_attribute($name, $value){
		$this->custom_attributes[$name] = $value;
	}
}
?>