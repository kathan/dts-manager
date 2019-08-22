<?php
require_once("select_input.php");
class column{
	var $name;
	var $length;
	var $type;
	var $hidden;
	var $protected;
	var $is_primary;
	var $is_unique;
	var $pw;
	var $omitted;
	var $regular_expressions = [];
	var $children = [];
	var $parent_column;
	var $parent_label_column;
	var $relative;
	var $feedback;
	var $not_null;
	var $auto_inc;
	var $label;
	var $id;
	var $table_obj;
	var $value_list;
	var $rows;
	var $cols;
	var $error_str;
	var $db_date_functions=['NOW()'];
	var $null_str ='';
	var $html_input;
	
	function __construct(&$table_obj, $name, $desc=true){
		$this->pw = false;
		$this->name = $name;
		$this->label = $name;
		$this->parent_label_column = $name;
		$this->table_obj =& $table_obj;
		if($desc){
			$this->_describe();
		}
	}
	
	function add_error($str){
		$this->error_str .= $str."<br>";
	}
	
	function set_rows($rows){
		$this->rows = $rows;
	}
	function set_cols($cols){
		$this->cols = $cols;
	}
	function set_value_list($vl){
		$this->value_list = $vl;
	}
	
	function set_id($id){
		$this->id = $id;
	}
	function set_label($label){
		$this->label = $label;
	}
	
	function get_name(){
		return $this->name;
	}
	
	function get_type(){
		return $this->type;
	}
	
	function get_table(){
		return $this->table_obj->get_name();
	}
	
	function _describe(){
		$this->children = db_get_children($this->table_obj, $this->name);
		$this->parent_column =& db_get_parent($this->table_obj, $this->name);
	}
	
	function get_child_link($value){
		require_once("filter_link.php");
		$new_value = "";
		if(count($this->children) > 0){
			foreach($this->children as $child){
				$new_value .= "<a href=\"".$child['TABLE_NAME'].".php?action=filter&$child[COLUMN_NAME]=$value\">".$child['TABLE_NAME']."</a><br>";
			}
		}
		return $new_value;
	}
	
	function get_parent(){
		return $this->parent_column;
	}
	
	
	function get_parent_link($value){
		require_once("filter_link.php");
		$new_value = "";

		if($this->parent_column){
			$new_value .= "<a href=\"".$this->parent_column->get_table().".php?action=filter&".$this->parent_column->get_name()."=$value\">".$this->parent_column->get_table()."</a><br>";	
		}
		
		return $new_value;
	}
	
	function get_view_html($value){
		if(isset($this->parent_column)){
			return $this->apply_reg_exp($this->get_parent_value($value));
		}else{
			switch($this->get_type()){
				case 'binary':
					if($value){
						return 'Yes';
					}else{
						return 'No';
					}
					break;
				case 'date':
					return $this->MySQL_Date_To_format($value, 'n/j/Y');
					break;
				default:
					return $this->apply_reg_exp($value);
					break;
			}
		}
	}
	
	function has_parent(){
		return isset($this->parent_column);
	}
	
	function MYSQL_Time_To_Format($value, $format){
		
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
		}
		return $this->null_str;
	}
	
	function &get_edit_html($value=null){
		
		if(isset($this->parent_column)){
			require_once("select_input.php");
			return new select_input($this->name, $this->parent_column->name, $this->parent_label_column, $this->get_parent_records(), $value);
		}elseif(isset($this->value_list)){
			return new select_input($this->name, '', $this->name, $this->value_list, $value);
		}
			
		if($this->pw){
			return new password_input($this->name, true);
		}elseif($this->hidden){
			//This should handle and date formats as well
			return new hidden_input($this->name, $value);
		}else{
			
			if(!isset($this->html_input)){
			switch($this->get_type()){
				case 'time':
					$this->html_input = new text_input($this->name, $this->MySQL_Date_To_format($value, 'g:i a'));
					if($this->table_obj->auto_save){
						$this->html_input->set_id($this->id);
					}
					
					$this->html_input->set_label($this->label);
					//return $i;
					//return new text_input($this->name, $value);
					break;
				case 'date':
					
					$this->html_input = new date_input($this->name, $this->MySQL_Date_To_format($value, 'n/j/Y'));
					if($this->table_obj->auto_save){
						$this->html_input->set_id($this->id);
					}
					$this->html_input->set_label($this->label);
					//return $i;
					//return new text_input($this->name, $value);
					break;
				case 'datetime':
				
					$this->html_input =  new date_input($this->name, $this->MySQL_Date_To_format($value, 'n/j/Y g:i a'));
					$this->html_input->set_label($this->label);
					if($this->table_obj->auto_save){
						$this->html_input->set_id($this->id);
					}
					//return $i;
					break;
				case 'timestamp':
					
					$this->html_input =  new date_input($this->name, $this->MySQL_Date_To_format($value, 'n/j/Y g:i a'));
					$this->html_input->set_label($this->label);
					if($this->table_obj->auto_save){
						$this->html_input->set_id($this->id);
					}
					//return $i;
					break;
				case 'binary':
					$this->html_input = new checkbox_input($this->name, $value);
					$this->html_input->set_label($this->label);
					if($this->table_obj->auto_save){
						$this->html_input->set_id($this->id);
					}
					//return $i;
					break;
				case 'mediumtext':
					$this->html_input =  new textarea_input($this->name, $value);
					if(isset($this->rows)){
						$this->html_input->set_rows($this->rows);
					}
					if(isset($this->cols)){
						$this->html_input->set_cols($this->cols);
					}
					$this->html_input->set_label($this->label);
					if($this->table_obj->auto_save){
						$this->html_input->set_id($this->id);
					}
					//return $i;
					break;
				case 'mediumblob':
					require_once("file_input.php");
					$this->html_input =  new file_input($this->name);
					$this->html_input->set_label($this->label);
					if($this->table_obj->auto_save){
						$this->html_input->set_id($this->id);
					}
					//return $i;
					break;
				default:
					
					if(isset($this->value_list)){
						
						return new select_input($this->name, '', $this->name, $this->value_list, $value);
					}else{
						$this->html_input =  new text_input($this->name, $value);
						if(isset($this->length)){
							if($this->table_obj->auto_save){
								$this->html_input->set_id($this->id);
							}
							if($this->length < 30){
								$this->html_input->set_size($this->length);
							}
							$this->html_input->set_max_length($this->length);
						}
						$this->html_input->set_label($this->label);
					}
					break;
			}
			}
			return $this->html_input;
		}
	}
		
	function format_for_db($value){
		switch($this->get_type()){
			case 'int':
				return intval($value);
				break;
			case 'decimal':
				return floatval($value);
				break;
			case 'time':
				if(in_array($value, $this->db_date_functions)){
					
					return $value;
				}
				return "'".$this->time_to_db($value)."'";
				break;
			case 'date':
				if(in_array($value, $this->db_date_functions)){
					return $value;
				}
				return "'".$this->date_to_db($value)."'";
				break;
			case 'datetime':
			
				if(in_array($value, $this->db_date_functions)){
					return $value;
				}
				return "'".$this->date_to_db($value)."'";
				break;
			case 'binary':
			
				return $this->cb_to_binary($value);
				break;
			case 'double':
				return intval($value);
				break;
			case 'mediumblob':
				return "'".$this->get_file()."'";
				break;
			default:
				return "'".DB::esc($value)."'";
				break;
		}
	}
	
	function format_for_where($value){
		/*
		1 =
		2 >
		3 >=
		4 <
		5 <=
		6 like
		*/
		switch($this->get_type()){
			case 'date':
				return "'".$this->date_to_db($value)."'";
				break;
			case 'datetime':
				return "'".$this->date_to_db($value)."'";
				break;
			case 'binary':
				return $this->cb_to_binary($value);
				break;
			case 'double':
				return intval($value);
				break;
			case 'mediumblob':
				return "'".$this->get_file()."'";
				break;
			default:
				return "'".DB::esc($value)."'";
				break;
		}
	}
	
	function date_to_db($origdate){
		if (isset($origdate) && $origdate != ''){
			return date("Y-m-d", strtotime($origdate));
		}
	}
	
	function time_to_db($origdate){
		if (isset($origdate) && $origdate != ''){
			return date("H:i:s", strtotime($origdate));
		}
	}
	
	function protect(){
		$this->protected = true;
	}
	
	function is_protected(){
		return $this->protected;
	}
	
	function hide(){
		$this->hidden = true;
	}
	
	function unhide(){
		$this->hidden = false;
	}
	
	function omit(){
		$this->omitted = true;
	}
	
	function insert(){
		$this->omitted = false;
	}
	
	function is_hidden(){
		return $this->hidden;
	}
	
	function is_omitted(){
		return $this->omitted;
	}
	
	function formatted_name(){
		return str_replace(' ', '&nbsp;', ucwords(str_replace('_', ' ', $this->label)));
	}
	
	function add_regular_expression($pattern, $replacement){
		$regular_expression = Array($pattern, $replacement);
		$this->regular_expressions[] = $regular_expression;
	}
	
	function add_reg_exp($pattern, $replacement){
		return $this->add_regular_expression($pattern, $replacement);
	}
	
	function apply_regular_expressions($value){
		foreach($this->regular_expressions as $regular_expression){
			$value = preg_replace($regular_expression[0], $regular_expression[1], $value);
		}
		return $value;
	}
	
	function apply_reg_exp($value){
		return $this->apply_regular_expressions($value);
	}
	
	function is_primary(){
		return $this->is_primary;
	}
	
	function set_parent_label_column($plc){
		$this->parent_label_column = $plc;
	}
	
	function get_parent_label_column(){
		return $this->parent_label_column;
	}
	
	function is_password(){
		if($this->pw){
			return true;
		}else{
			return false;
		}
	}

	function set_as_password(){
		$this->pw = true;
	}
	
	function add_feedback($feedback){
		$this->feedback .= "$feedback<br />"; 
	}
	
	function CB_To_Binary($field){
		if($field == "true"){
			return 1;
		}else{
			return 0;
		}
	}
	
	function check_input(&$input){
		$failure = false;
		//check for unique
		if($this->is_unique){
			$sql = "	SELECT *
						FROM `".$this->table_obj->get_name()."`
						WHERE `$this->name` = ?";
			$binds = [$input];
			$r = DB::query($sql);
		
			if(DB::num_rows($r) > 0){
				$this->add_error("$this->name must be unique.");
				$failure = true;
			}
		}
		
		//check for not null
		if($this->not_null && !$this->auto_inc && $input == ''){
			$this->add_error("$this->name is required.");
			$failure = true;
		}
		
		switch($this->get_type()){
			case 'int':
				if(!is_numeric($input) && $input != ''){
					$this->add_error("$this->name must be an integer. \"$input\" is not an integer");
					$failure = true;
				}
				break;
			case 'decimal':
				if(!is_numeric($input) && $input != ''){
					$this->add_error("$this->name must be a decimal.");
					$failure = true;
				}
				break;
			default:
				break;
		}
		return !$failure;
	}
	
	function get_file(){
		
		// In PHP earlier then 4.1.0, $HTTP_POST_FILES  should be used instead of $_FILES.
		$error = $_FILES[$this->name]['error'];
		if (is_uploaded_file($_FILES[$this->name]['tmp_name'])){
			//put the file contents into a variable and escape special characters
    		$image_data = DB::esc(file_get_contents($_FILES[$this->name]['tmp_name']));
	    	unlink($_FILES[$this->name]['tmp_name']);//delete the temporary file
	    }else{
    		$this->add_error("Error: $error<br>");
    	}
    	
		switch ($error){
		case 0:
			$this->add_feedback("Your file has been saved.");
			return $image_data;
		case 1: //UPLOAD_ERR_INI_SIZE
			$this->add_error("Error: $error The uploaded file exceeds the maximum server upload size.");
			break;
		case 2:
			$this->add_error("Error: $error The uploaded file exceeds the size that was specified in the html form.");
			break;
		case 3:
			$this->add_error("Error: $error The uploaded file was only partially uploaded.");
			break;
		case 4:
			$this->add_error("Error: $error No file was uploaded.");
			break;
		}
	}
}
?>