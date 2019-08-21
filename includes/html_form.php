<?php
require_once("text_input.php");
require_once("date_input.php");
require_once("password_input.php");
require_once("submit_input.php");
require_once("hidden_input.php");
require_once("textarea_input.php");
require_once("checkbox_input.php");
require_once("radio_group.php");
require_once("radio_input.php");
require_once("cancel_input.php");
class html_form{
	var $name;
	var $inputs = [];
	var $html_objects = [];
	var $method;
	var $action;
	var $in_table;
	var $edit_class='edit_class';
	var $label_class='label_class';
	
	function __construct($action="", $n=""){
		$this->name = $n;
		$this->set_post();
		if($action){
			$this->action = $action;
		}else{
			$this->action = $_SERVER['SCRIPT_NAME'];
		}
		$this->in_table = true;
	}
	
	function set_action($a){
		$this->action = $a;
	}
	
	function set_get(){
		$this->method = 'GET';
	}
	
	function set_post(){
		$this->method = 'POST';
	}
	
	
	
	function render(){
		$code = "";
		if($this->in_table) $code = "<table class='$this->edit_class'>\n";
		$code .= "\n<form method=\"$this->method\" action=\"$this->action\" name=\"$this->name\" enctype=\"multipart/form-data\">\n";
		foreach($this->inputs as $input){
			//$label = $input->get_label();
			if($this->in_table && get_class($input) != 'hidden_input')$code .= "\n<tr>\n";
			if($this->in_table && get_class($input) != 'hidden_input')$code .= "\n<td class='$this->label_class'>\n";
			$code .= $input->get_label()."\n";
			if($this->in_table && get_class($input) != 'hidden_input')$code .= "\n</td>\n";
			if($this->in_table && get_class($input) != 'hidden_input')$code .= "\n<td class='$this->edit_class'>\n";
			$code .= $input->render();
			//echo get_class($input)."<br />";
			if($this->in_table && get_class($input) != 'hidden_input')$code .= "\n</td>\n";
			if($this->in_table && get_class($input) != 'hidden_input')$code .= "\n</tr>\n";
		}
		$code .= "\n</form>\n";
		foreach($this->html_objects as $o){
			if($this->in_table && get_class($o) != 'hidden_input')$code .= "<tr>\n";
			if($this->in_table && get_class($o) != 'hidden_input')$code .= "<td>\n";
			$code .= $o->get_label()."\n";
			if($this->in_table && get_class($o) != 'hidden_input')$code .= "</td>\n";
			if($this->in_table && get_class($o) != 'hidden_input')$code .= "<td>\n";
			$code .= $o->render();
			if($this->in_table && get_class($o) != 'hidden_input')$code .= "</td>\n";
			if($this->in_table && get_class($o) != 'hidden_input')$code .= "</tr>\n";
		}
		
		if($this->in_table)$code .= "</table>\n";
		return $code;
	}
	
	function add_object(&$object){
		$this->html_objects[$object->get_name()] = $object;
	}
	
	function add_input(&$input){
		$this->inputs[$input->get_name()] = $input;
	}
	
	function create_input($type, $name, $value=""){
		$new_input = new html_input($type, $name, $value);
		$this->add_input($new_input);
		return $new_input;
	}
	
	function create_text_input($name, $value=""){
		$new_input = new text_input($name, $value);
		$this->add_input($new_input);
		return $new_input;
	}
	
	function create_password_input($name){
		$new_input = new password_input($name);
		$this->add_input($new_input);
		return $new_input;
	}
	
	function create_submit_input($name, $value=""){
		$new_input = new submit_input($name, $value);
		$this->add_input($new_input);
		return $new_input;
	}
	
	function create_cancel_input($name, $value=""){
		$new_input = new cancel_input($name, $value);
		$this->add_input($new_input);
		return $new_input;
	}
	
	function create_hidden_input($name, $value=""){
		$new_input = new hidden_input($name, $value);
		$this->add_input($new_input);
		return $new_input;
	}
	
	function create_textarea_input($name, $value=""){
		$new_input = new textarea_input($name, $value);
		$this->add_input($new_input);
		return $new_input;
	}
	
	function create_checkbox_input($name, $value=false){
		$new_input = new checkbox_input($name, $value);
		$this->add_input($new_input);
		return $new_input;
	}
	
	function create_radio_input($name, $value, $checked=false){
		$new_input = new radio_input($name, $value, $checked);
		$this->inputs[$value] = $new_input;
		return $new_input;
	}
	
	function &create_radio_group($name, $value=false){
		$new_input = new radio_group($name, $value);
		$this->add_input($new_input);
		return $new_input;
	}
	
	function get_name(){
		return $this->name;
	}
	
	function get_label(){
		return $this->get_name();
	}
}
?>