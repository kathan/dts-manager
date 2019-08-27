<?php
global $feedback;
	
function safe_define($key, $value){
    if(!defined($key)){
	define($key, $value);
    }
}

function array2query(){
    $args = func_get_args();
    $ary = $args[0];
    unset($args[0]);
    $ex = $args;
    if(is_array($ary)){
	$i=0;
	$str = '';
	foreach($ary as $key => $val){
            if(!in_array($key, $ex)){
		$i > 0 ? $str .= '&amp;' : '';
		$str .= "$key=".urlencode($val);
		$i++;
            }
	}
    }
    return $str;
}
	
function nbsp($str){
    return str_replace(" ", '&nbsp;', $str);
}

function get_action(){
    if(isset($_REQUEST['action'])){
	return $_REQUEST['action'];
    }else{
        return '';
    }
}
	
function &safe_get(&$v){
    if(isset($v)){
		$v2 =& $v;
        return $v2;
	}
	return null;
}
	
function ob_get_output($file){
    ob_start();
    include ($file);
    return ob_get_clean();
}
	
function set_post($key, $value){
    $_REQUEST[$key] = $value;
    $_POST[$key] = $value;
}
	
function unset_post($key){
    unset($_REQUEST[$key]);
    unset($_POST[$key]);
}
	
function action_is($action){
    if(isset($_REQUEST['action']) && $_REQUEST['action'] == $action){
	return true;
    }else{
	return false;
    }
}
	
function dateToMySQL($origdate){
    if (isset($origdate)){
	return date("Y-m-d h:m:s", strtotime($origdate));
    }
}
	
function MySQL_Date_To_format($mysqldate, $format){
    if(isset($mysqldate) && $mysqldate != ''  && $mysqldate != '0000-00-00'){
	$date_time_ary = explode( ' ', $mysqldate);
	$date_ary = explode( '-', $date_time_ary[0]);
	$date_str ='';
	if(isset($date_ary[1]) && isset($date_ary[2]) && $date_ary[0]){
            $date_str .= $date_ary[1].'/'.$date_ary[2].'/'.$date_ary[0]." ";
	}else{
            $date_str .= '00/00/0000';
	}
	$date_str .= safe_get($date_time_ary[1]);
	return date($format, strtotime($date_str));
    }else{
	return date($format);
    }
}
	
function logError($err, $function){
    
}
	
function getFileName($path){
    $pathAry = explode("/", $path);
    if ($pathAry[0] == ""){
	unset($pathAry[0]);
    }
    if ($pathAry[count($pathAry)] == ""){
        unset($pathAry[count($pathAry)]);
    }
    return $pathAry[count($pathAry)];
}
	
	function array_to_stringOLD($array){
		foreach ($array as $index => $val){
			$val2 .= " ".$val;
		}
		return $val2;
	} 
	
	 function array_to_string($array){
		$str='';
		if(is_array($array)){
			$keys = array_keys($array);
			foreach($keys as $key){
				$str .= $key . ":" . $array[$key] . "\n";
			}
			return $str;
		}
	}
	
	function validate_email ($address){
		return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $address));
	}
	
	function get_file($name){
		global $feedback;
		// In PHP earlier then 4.1.0, $HTTP_POST_FILES  should be used instead of $_FILES.
		if (is_uploaded_file($_FILES[$name]['tmp_name'])){
			//put the file contents into a variable and escape special characters
    		$image_data = DB::esc(file_get_contents($_FILES[$name]['tmp_name']));
	    	unlink($_FILES[$name]['tmp_name']);//delete the temporary file
	    }else{
	    	$error = $_FILES[$name]['error'];
    		$feedback .= "Error: $error<br>";
    	}
    	
		switch ($error){
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