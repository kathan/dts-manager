<?php
require_once("html_input.php");
class select_input extends html_input{
	var $options;
	var $selected;
	var $id_name;
	var $label_name;
	
	function __construct($name, $id_name, $label_name, $options, $selected=null){
		$this->options = $options;//the db resource with the list data
		$this->id_name = $id_name;//The name of the column where the id value comes from (parent id)
		$this->label_name = $label_name;//the name of the column where the visible user data (parent label)
		$this->selected = $selected;//the selected id value
		parent::__construct('select', $name);
		
	}
	
	function render(){
		$code = "
			<select name=\"$this->name\" id=\"$this->id\" ";
		$keys = array_keys($this->custom_attributes);
		foreach($keys as $name){
			$code .= " $name='".$this->custom_attributes[$name]."'";
		}
		$code .= ">
				".$this->get_options()."
			</select>";
		return $code;
	}
	
	function get_options(){
		$code ='';
		if(!isset($this->selected)){
			$code .= "<option></option>";
		}
		if(is_resource($this->options)){
			while($row = db_fetch_array($this->options)){
				if (safe_get($row[$this->id_name]) == $this->selected){
					if($row[$this->label_name] != ''){
						$code .= "
						<option value=\"".$row[$this->id_name]."\" selected>".$row[$this->label_name]."</option>";
					}
				}else{
					if($row[$this->label_name] != ''){
						$code .= "
						<option value=\"".$row[$this->id_name]."\">".$row[$this->label_name]."</option>";
					}
				}
			}
		}elseif(is_array($this->options)){
			$keys = array_keys($this->options);
			foreach($keys as $key){
				if($key == $this->selected){
					$code .= "
					<option value=\"$key\" selected>".$this->options[$key]."</option>";
				}else{
					$code .= "
					<option value=\"$key\">".$this->options[$key]."</option>";
				}
			}
		}
		return $code;
	}
}
?>