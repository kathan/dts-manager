<?php
function &table($obj=null, $attribs=null){
	$t = new html_table($obj);

	if(isset($attribs)){
		set_attribs($t, $attribs);
	}
	return $t;
}

function &thead($obj=null, $attribs=null){
	$t = new html_thead($obj);

	if(isset($attribs)){
		set_attribs($t, $attribs);
	}
	return $t;
}

function &tbody($obj=null, $attribs=null){
	$t = new html_tbody($obj);

	if(isset($attribs)){
		set_attribs($t, $attribs);
	}
	return $t;
}

function &tr($obj=null, $attribs=null){
	$t = new html_tr($obj);

	if(isset($attribs)){
		set_attribs($t, $attribs);
	}
	return $t;
}

function &td($obj=null, $attribs=null){
	$t = new html_td($obj);

	if(isset($attribs)){
		set_attribs($t, $attribs);
	}
	return $t;
}

//===== HTML Classes ====
class html_thead extends html_object
{
	function html_thead($inner=null){
		$this->html_object($inner);
		$this->type = 'thead';
	}
}

class html_tbody extends html_object
{
	function html_tbody($inner=null){
		$this->html_object($inner);
		$this->type = 'tbody';
	}
}

class html_td extends html_object
{
	function html_td($inner=null){
		$this->html_object($inner);
		$this->type = 'td';
	}
}

class html_tr extends html_object
{
	function html_tr($inner=null){
		$this->html_object($inner);
		$this->type = 'tr';
	}
}

class html_table extends html_object
{
	function html_table($inner=null){
		$this->html_object($inner);
		$this->type = 'table';
	}
}

class html_object
{
	var $inner;
	var $attributes=[];
	var $type;
	
	function html_object($inner=null){
		if(isset($inner)){
			$this->inner = $inner;
		}
	}
	
	function render(){
		$c = "<$this->type>\n";
		if(isset($this->inner)){
			//echo get_class($this->inner);
			if(is_subclass_of($this->inner, 'html_object')){
				$c .= $this->inner->render();
			}elseif(is_string($this->inner)){
				$c .= $this->inner;
			}
		}
		$c .= "</$this->type>\n";
		return $c;
	}
	
	function set_attribute($name, $value){
		$this->attributes[$name] = $value;
	}
}

function &set_attribs(&$obj, &$attribs){
	$attrib_names = array_keys($attribs);
	foreach($attrib_names as $attrib_name){
		$obj->set_attribute($attrib_name, $attribs[$attrib_name]);
	}
}
?>