<?php
	
	function checkbox_to_binary($value)
	{
		//$value: the value from the checkbox control
		if($value == "on")
		{
			return 1;
		}else{
			return 0;
		}
	}
	
	function varchar_to_text($value, $name)
	{
		//$value: the varchar value from the database field
		return "<input type=\"text\" name=\"$name\" value=\"$value\">";
	}
	
	function text_to_textarea($value, $name)
	{
		//$value: the text value from the database field
		return "<textarea name=\"$name\">$value</textarea>";
	}
	
	function text_to_varchar($value)
	{
		//$value: the value from the text input field
		return addslashes($value);
	}
	
	function binary_to_checkbox($value, $name)
	{
		//$value: the binary value from the database field
		//$name: then name of the checkbox control
		if ($value == 1)
		{
			return "<input type=\"checkbox\" name=\"$name\" checked>";
		}else{
			return "<input type=\"checkbox\" name=\"$name\">";
		}
	}
	
	function array_to_select($option_id, $option_name, $result, $select_name, $select_id=null, $multiple=0)
	{	
		if ($result)
		{
			if($multiple)
			{
				$code = "<select name=\"$select_name\"[] multiple>\n";
			}else{
				$code = "<select name=\"$select_name\">\n";
				$code .= "<option value=\"0\"></option>\n";
			}
		
			while ($row = db_fetch_assoc($result))
			{
				$option_value = $row[$option_id];
				$option = $row[$option_name];
				if ($row[$option_id] == $select_id)
				{
					$code .= "<option value=\"$option_value\" selected>$option</option>\n";
				}else{
					$code .= "<option value=\"$option_value\">$option</option>\n";
				}
			}
			$code .= "</select>";
		}
		
		return $code;
	}
?>