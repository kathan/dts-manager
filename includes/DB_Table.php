<?php
require_once('global.php');
require_once('DB.php');
require_once('column.php');
require_once('feedback.php');

class DB_Table{
	public static $save_str = 'Save';
	public static $add_str = 'Add';
	public static $delete_str = 'Delete';
	public static $columns = [];
	public static $name;
	public static $table;
	public static $feedback;
	private $feedback_ary=[];
	public static $error_str;
	public static $conn;
	public static $sql;
	public static $binds = [];
	private $dbname;
	private $debug=0;
	
	function __construct($table){
		/*include_once('Version.php');
		Version::add(__FILE__, .1);*/
		
		$this->name = $table;
		$this->tables = $table;
		$this->describe();
	}
	
	function debug(){
		$this->debug = 1;
	}

	function json_reply(){
		$r = [];
		if(isset($this->error_str)){
			$r['error'] = true;
			$r['errorMsg'] = $this->error_str;
		}
		$r['feedback'] = $this->feedback_ary;
		if($this->debug){
			$r['sql'] = $this->sql;
		}
		return json_encode($r);
	}
	
	function close(){
		DB::close();
	}
	
	function get_name(){
		return $this->name;
	}
	
	private function describe(){
		/*Query for all columns in the table and */
		$this->sql = "Describe `$this->name`";
		$r = App::$db->query($this->sql);
		if(!$r->errorCode() && $r){
			while($row = $r->fetch(PDO::FETCH_ASSOC)){
				if(!isset($this->columns[$row['Field']])){
					
					$column = new column($this, $row['Field'], false);
					$column->actual = true;
					$search = '/(\w+)(\((\d+)\))?/';
					preg_match($search, $row['Type'], $result);
					if(isset($result[1])){
						$column->type = $result[1];
					}else{
						$column->type = 'unknown';
					}
			
					if(isset($result[3])){
						$column->length = $result[3];
					}
					switch($row['Key']){
						case 'PRI':
							$column->is_primary = true;
							break;
						case 'UNI':
							$column->is_unique = true;
							break;
					}
					if($row['Null'] == 'NO'){
						$column->not_null = true;
					}
					if($row['Extra'] == 'auto_increment'){
						$column->auto_inc = true;
					}
					if($column->error_str){
						$this->add_error($column->error_str);
					}
					$this->columns[$row['Field']] = $column;
				}
			}
		}
	}
	
	function execute(){
		switch($this->get_action()){
			
			case DB_Table::$delete_str:
				return $this->delete($_POST);
				break;
			case DB_Table::$save_str:
				return $this->update($_POST);
				break;
			case DB_Table::$add_str:
				return $this->add($_POST);
				break;
		}
	}
	
	function insert($input){
		return $this->add($input);
	}
	
	function add($input){
		if($this->check_data($input)){
			$field_names = '';
			$values = "";
			$this->sql = "INSERT INTO `".$this->name ."` (";
			$fields = array_keys(array_merge($input, $_FILES));
			
			foreach($fields as $field){	
				if($field != 'action' && $field != DB_Table::$save_str && array_key_exists($field, $this->columns)  && $this->columns[$field]->actual && ((isset($input[$field]) && $input[$field] != '') || isset($_FILES[$field]))){
					if($field_names != ''){
						$field_names .= ', ';
						$values .= ",";
					}
					$field_names .= "`$field`";
					
					$values .= $this->columns[$field]->format_for_db($this->safe_get($input[$field]));
					
					if($this->columns[$field]->error_str){
						$this->add_error($this->columns[$field]->error_str);
					}
				}
			}
			$this->sql .= "$field_names) VALUES ($values)";
			$r = App::$db->query($this->sql);
			
			if($r->errorCode()){
				$this->add_error($r->errorCode(), __FILE__, __FUNCTION__);
				return false;
			}else{
			
				$this->add_feedback("New record was added.");
				
				$this->last_id = App::$db->lastInsertId();
				return true;
			}
		}else{
			$this->add_feedback("Data was not added.");
		}
		
	}
    
	function update($set_ary, $where_ary){
		$pks = $this->get_primary_keys();
		
		$set = '';
		$this->sql = "UPDATE `".$this->name."`
		SET ";
		
		foreach($set_ary as $key => $val){
			if(isset($this->columns[strtolower($key)])){
				if(!$set == ''){
					$set .= ', ';
				}
				$set .= "`$key` = ?";
			
				$this->binds[] = $val;
			}
		}
		$this->sql .= $set;
		$clause = 'WHERE';
		foreach($where_ary as $key => $val){
			if(isset($this->columns[strtolower($key)])){
				$this->sql .= " $clause `$key` = ?";
				$this->binds[] = $val;
				$clause = 'AND';
			}
		}
		$result = App::$db->query($this->sql, $this->binds);
		if($result){
			$this->add_error($result->errorCode());
			$this->add_feedback("Data was not updated.");
			return false;
		}else{
			$this->add_feedback("Updated.");
			return true;
		}
	}
	
	function delete($pk_vals){
		
		$pks = $this->get_primary_keys();
		
		$this->sql = "DELETE FROM ".$this->name;
		$clause = 'WHERE';
		foreach($pks as $pk_obj){
			$pk = $pk_obj->get_name();
			$this->sql .= " $clause $pk = ".$pk_obj->format_for_db($pk_vals[$pk]);
			$clause = 'AND';
			
		}
		$result = App::$db->query($this->sql);
		if($result->errorCode()){
			$this->add_error($result->errorCode());
			$this->add_error($this->sql);
			$this->add_error('table.delete');
			return false;
		}else{
			$this->add_feedback("ID $pk_vals[$pk] was deleted.");
			return true;
		}
	}
	
	function check_data(&$input){
		$failure = false;
		$fields = array_keys($input);
		foreach($fields as $field){
			if($field != 'action' && $field != DB_Table::$save_str && array_key_exists($field, $this->columns)){
				
				$c = $this->columns[$field];
				if(!$c->check_input($input[$field])){
					$failure = true;
					$this->add_feedback($c->feedback);
					$this->add_error($c->error_str);
				}
			}
		}
		
		return !$failure;
	}
        
	function add_feedback($feedback){
		if($feedback != ''){
			$this->feedback .= "$feedback<br />";
			$this->feedback_ary[] = $feedback;
		}
	}
	

	function add_error($str){
		$str != '' ? $this->error_str .= "$str<br />" : '';
	}
	function get_primary_keys(){
		$column_keys = array_keys($this->columns);
		$pk = [];
		foreach($column_keys as $key){
			$column =& $this->columns[$key];
			if($column->is_primary()){
				$pk[] = $this->columns[$key];
			}
		}
		return $pk;
	}
	//==== DB Calls ====
	
	
	function table_check(&$columns){
		$ok = true;
		
		$col_names = array_keys($columns);
		
		foreach($col_names as $col_name){
			
			
			if(!array_key_exists($col_name, $this->columns)){
				$ok = false;
				$this->add_error("The \"$col_name\" column does not exist in the \"$this->name\" table of the \"$this->dbname\" database.");
			}else{
				
				$ok = $this->columns[$col_name]->check_type($columns[$col_name]);
			}
		}
		return $ok;
	}
	
	function add_column($new_col, $type){
		return App::$db->query("ALTER TABLE `$this->name` ADD `$new_col` $type");
		
	}
	
	function alter_column($col, $type){
		return App::$db->query("ALTER TABLE `$this->name` CHANGE `$col` `$col` $type");
	}
	
	function drop_columns($cols){
		$this->sql = "ALTER TABLE `$this->name` ";
  
		$i = 1;
		foreach($cols as $col){
			if($i > 1){
				$this->sql .= ',';
			}
			$this->sql .= "DROP `$col`";
			$i++;
		}
		return App::$db->query($this->sql);
	}
	public static function &safe_get(&$v){
		if(isset($v)){
			return $v;
		}else{
			return '';
		}
	}

	static function refValues($arr){
		if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
		{
			$refs = [];
			foreach($arr as $key => $value){
				$refs[$key] = &$arr[$key];
			}
			return $refs;
		}
		return $arr;
	}
}
?>