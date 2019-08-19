<?php
	global $feedback;
	
	require_once("database.php");
	
	function safe_define($key, $value)
	{
		if(!defined($key))
		{
			define($key, $value);
		}
	}
	
	function nbsp($str)
	{
		return str_replace(" ", '&nbsp;', $str);
	}
	function get_action()
	{
		if(isset($_REQUEST['action']))
		{
			return $_REQUEST['action'];
		}else{
			return '';
		}
	}
	
	function &safe_get(&$v)
	{
		if(isset($v))
		{
			return $v;
		}else{
			return '';
		}
	}
	
	function ob_get_output($file)
	{
		ob_start();
		include ($file);
		return ob_get_clean();
		
	}
	
	function set_post($key, $value)
	{
		$_REQUEST[$key] = $value;
		$_POST[$key] = $value;
	}
	
	function unset_post($key)
	{
		unset($_REQUEST[$key]);
		unset($_POST[$key]);
	}
	
	function action_is($action)
	{
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == $action)
		{
			return true;
		}else{
			return false;
		}
	}
	
	function dateToMySQL($origdate)
	{
		if (isset($origdate))
		{
			return date("Y-m-d h:m:s", strtotime($origdate));
		}
	}
	
	function MySQL_Date_To_format($mysqldate, $format)
	{
		if(isset($mysqldate) && $mysqldate != ''  && $mysqldate != '0000-00-00')
		{
			$date_time_ary = explode( ' ', $mysqldate);
			$date_ary = explode( '-', $date_time_ary[0]);
			$date_str ='';
			if(isset($date_ary[1]) && isset($date_ary[2]) && $date_ary[0])
			{
				$date_str .= $date_ary[1].'/'.$date_ary[2].'/'.$date_ary[0]." ";
			}else
			{
				$date_str .= '00/00/0000';
			}
			$date_str .= safe_get($date_time_ary[1]);
			//print_r($date_ary);
			//echo $date_str;
			return date($format, strtotime($date_str));
		}else
		{
			return date($format);
		}
	}
	
	function logError($err, $function)
	{
		$sql ="INSERT INTO errors(
								error_string,
								server_values,
								function,
								request_values
								)
								values(
								'$err',
								'".formToDB(array_to_string($_SERVER))."',
								'$function',
								'".formToDB(array_to_string($_REQUEST))."'
								)";
		db_query($sql);
		if( db_error())
		{
			//echo db_error();
			//echo $sql;
		}
	}
	
	function getFileName($path)
	{
		$pathAry = explode("/", $path);
		if ($pathAry[0] == "")
		{
			unset($pathAry[0]);
		}
		if ($pathAry[count($pathAry)] == "")
		{
			unset($pathAry[count($pathAry)]);
		}
		return $pathAry[count($pathAry)];
	}
	
	function array_to_stringOLD($array)
	{
		foreach ($array as $index => $val)
		{
			$val2 .= " ".$val;
		}
		return $val2;
	} 
	
	 function array_to_string($array)
	{
		$str='';
		if(is_array($array))
		{
			$keys = array_keys($array);
			foreach($keys as $key)
			{
				$str .= $key . ":" . $array[$key] . "\n";
			}
			return $str;
		}
	}

	function getSelect($option, $select_name, $select_id, $result, $multiple=0)
	{
	
		$option_id = $option . "_id";
		
		if($multiple)
		{
			$code = "<select name=".$select_name."[] multiple>\n";
		}else{
			$code = "<select name='$select_name'>\n";
			$code .= "<option value='0'></option>\n";
		}
		
		
		if ($result)
		{
			while ($row = db_fetch_assoc($result))
			{
				$option_value = $row[$option_id];
				$option_name = $row[$option];
				//debug("value=$value");
				//debug("name=$name");
				if ($row[$option_id] == $select_id)
				{
					
					$code .= "<option value='$option_value' selected>$option_name</option>\n";
				}else{
					$code .= "<option value='$option_value'>$option_name</option>\n";
				}
			}
		}
		$code .= "</select>";
		return $code;
	}
	
	function getSelectNew($option_id, $option_name, $result, $select_name, $select_id=null, $multiple=0)
	{	
		if($multiple)
		{
			$code = "<select name=".$select_name."[] multiple>\n";
		}else{
			$code = "<select name='$select_name'>\n";
			$code .= "<option value='0'></option>\n";
		}
		
		
		if ($result)
		{
			while ($row = db_fetch_assoc($result))
			{
				$option_value = $row[$option_id];
				$option = $row[$option_name];
				//debug("value=$value");
				//debug("name=$name");
				if ($row[$option_id] == $select_id)
				{
					
					$code .= "<option value='$option_value' selected>$option</option>\n";
				}else{
					$code .= "<option value='$option_value'>$option</option>\n";
				}
			}
		}
		$code .= "</select>";
		return $code;
	}
	
	function getView($sql)
	{
		global $feedback;
		$result = db_query($sql);
		$feedback .= db_error();
		$view .= "
		<center>
			<table class='viewTable'>
				<tr>";
		$fieldCount = db_numfields($result);
		
		//Show all the field heads
		for($i=0;$i < $fieldCount;$i++)
		{
			$view .= getCell(db_fieldname($result, $i), "listTableHead");
		}
		$view .= "</tr>";
		while ($row = db_fetch_array($result))
		{
			$r++;
			$view .= "
				<tr>";
				if ($r % 2 == 0)
				{
					$class = "listTableData1";
				}else{
					$class = "listTableData2";
				}
			for($i=0; $i < $fieldCount; $i++)
			{
				
				$view .= getCell($row[$i], $class);
			}
			//$view .= "		</div>";
		}
		$view .= "
				</tr>
		</center>";
		return $view;
	}
	
	function validate_email ($address) 
	{
		return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $address));
	}
	
	function get_file($name)
	{
		global $feedback;
		// In PHP earlier then 4.1.0, $HTTP_POST_FILES  should be used instead of $_FILES.
		if (is_uploaded_file($_FILES[$name]['tmp_name']))
		{
			//put the file contents into a variable and escape special characters
    		$image_data = mysql_real_escape_string(file_get_contents($_FILES[$name]['tmp_name']));
	    	unlink($_FILES[$name]['tmp_name']);//delete the temporary file
	    }else{
	    	$error = $_FILES[$name]['error'];
    		$feedback .= "Error: $error<br>";
    	}
    	
		switch ($error)
		{
		case 0:
			$feedback .=  "Your file has been saved.";
			return true;
		case 1: //UPLOAD_ERR_INI_SIZE
			$feedback .=  "Error: $error The uploaded file exceeds the maximum server upload size.";
			break;
		case 2:
			$feedback .=  "Error: $error The uploaded file exceeds the size that was specified in the html form.";
			break;
		case 3:
			$feedback .=  "Error: $error The uploaded file was only partially uploaded.";
			break;
		case 4:
			$feedback .=  "Error: $error No file was uploaded.";
			break;
		}
		return $image_data;
	}
?>