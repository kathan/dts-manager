<?php
class hyperlink{
	var $value;
	var $location;
	var $params = [];
	
	function hyperlink($location, $value, $params=null){
		$this->location = $location;
		$this->value = $value;
		if($params){
			
			$this->params = $params;
			
		}
	}
	
	function render(){
		return "<a href=\"$this->location".$this->build_query_string()."\">$this->value</a>";
	}
	
	function add_param($key, $value){
		$this->params[$key] = $value;
	}
	
	function build_query_string(){
		$i = 0;
		foreach ($this->params as $key => $value){
			if($i == 0){
				$str = "?$key=$value";
			}else{
				$str .= "&$key=$value";
			}
			$i++;
		}
		return $str;
	}
}
?>