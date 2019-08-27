<?php
require_once('DB.php');
require_once('DbColumn.php');

class DbTable{
	const PHP_TO_DB_FORMAT = 'Y-m-d H:i:s';
	public static $columns = [];
	public static $name;
	public $error_ary = [];
	public static $conn;
	public static $sql;
	public static $binds = [];
	private $dbname;
	private $db;
	private $debug=0;
	
	function __construct(PDO $db, $table){
		/*include_once('Version.php');
		Version::add(__FILE__, .1);*/
		$this->db = $db;
		$this->name = $table;
		$this->tables = $table;
		$this->describe();
	}
	
	function debug(){
		$this->debug = 1;
	}
	
	function getName(){
		return $this->name;
	}
	
	private function describe(){
		/*Query for all columns in the table and */
		$this->sql = "Describe `$this->name`";
		$r = $this->db->query($this->sql);
		if($this->db->errorCode() > 0){
			echo $this->db->errorCode();
		}else{
			while($row = $r->fetch(PDO::FETCH_ASSOC)){
				if(!isset($this->columns[$row['Field']])){
					
					$column = new DbColumn($this, $row['Field'], false);
					$column->actual = true;
					$search = '/(\w+)(\((\d+)\))?/';
					preg_match($search, $row['Type'], $result);
					if(isset($result[1])){
						$column->setType($result[1]);
					}else{
						$column->setType('unknown');
					}
			
					if(isset($result[3])){
						$column->setLength($result[3]);
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
						$this->addError($column->error_str);
					}
					$this->columns[$row['Field']] = $column;
				}
			}
		}
	}
	
	function query($columns_ary, $where_ary, $page){
		$limitOffset = '';
		if(is_array($page)){
			isset($page[0]) ? $limitOffset = "LIMIT ".$page[0] : $limitOffset = '';
			isset($page[1]) ? $limitOffset .= ", ".$page[1] : '';
		}
		$this->binds = [];
		$select = '';
		foreach($columns_ary as $column){
			if(array_key_exists(strtolower($column), $this->columns) || $column = '*'){
				if($select != ''){
					$select .= ',';
				}
				$select .= "$column";
			}
		}
		$where = '';
		$clause = 'WHERE';
		foreach($where_ary as $key => $val){
			if(array_key_exists(strtolower($key), $this->columns)){
				if($where != ''){
					$where .= ',';
				}
				$where .= " $clause `$key` = ?";
				$this->binds[] = $this->format($val);
				$clause = 'AND';
			}
		}
		$this->sql = "SELECT $select FROM `$this->name` $where $limitOffset";
		try {
			$stmt = $this->db->prepare($this->sql);
			$result = $stmt->execute($this->binds);
			return $stmt;
		} catch(PDOExecption $e) { 
			$this->addError($e->getMessage());
			return false;
		}
	}

	function insert($input){
		return $this->add($input);
	}
	
	function add($input){
		$this->binds = [];
		foreach($input as $key => $val){	
			if(array_key_exists(strtolower($key), $this->columns)){
				if($field_names != ''){
					$field_names .= ',';
					$values .= ",";
				}
				$field_names .= "`$key`";
				
				$values .= '?';
				$this->binds[] = $this->format($val);
			}
		}
		$this->sql = "INSERT INTO `".$this->name ."` ($field_names) VALUES ($values)";

		try {
			$stmt = $this->db->prepare($this->sql);
			$result = $stmt->execute($this->binds);
		} catch(PDOExecption $e) { 
			$this->addError($e->getMessage());
			return false;
		}
		$this->last_id = $this->db->lastInsertId();
		return true;
	}
	
	private function format($val){
		if(gettype($val) === 'object'){
			switch(get_class($val)){
				case 'DateTime':
					return $val->format(self::PHP_TO_DB_FORMAT);
					break;
			}
		}
		return $val;
	}

	function update($set_ary, $where_ary){
		$this->binds = [];
		$set = '';
		$this->sql = "UPDATE `".$this->name."` \nSET ";
		
		foreach($set_ary as $key => $val){
			if(array_key_exists(strtolower($key), $this->columns)){
				if(!$set == ''){
					$set .= ', ';
				}
				$set .= "`$key` = ?";
				$this->binds[] = $this->format($val);
			}
		}

		$this->sql .= $set;
		$clause = 'WHERE';
		foreach($where_ary as $key => $val){
			if(array_key_exists(strtolower($key), $this->columns)){
				$this->sql .= " $clause `$key` = ?";
				$this->binds[] = $this->format($val);
				$clause = 'AND';
			}
		}
		$stmt = $this->db->prepare($this->sql);
		$result = $stmt->execute($this->binds);
		if(!$result) { 
			return false;
		}
		return true;
	}
	
	function delete($delete_ary){
		$this->binds = [];		
		$this->sql = "DELETE FROM `".$this->name."`";
		$clause = 'WHERE';
		foreach($delete_ary as $key => $val){
			if(array_key_exists(strtolower($key), $this->columns)){
				$this->sql .= " $clause `$key` = ?";
				$this->binds[] = $this->format($val);
				$clause = 'AND';
			}
		}
		$stmt = $this->db->prepare($this->sql);
		return $stmt->execute($this->binds);
	}
	
	function addError($str){
		$str != '' ? $this->error_ary[] = $str : '';
	}

	function getPrimaryKeys(){
		$column_keys = array_keys($this->columns);
		$pk = [];
		foreach($column_keys as $key){
			$column =& $this->columns[$key];
			if($column->isPrimary()){
				$pk[] = $this->columns[$key];
			}
		}
		return $pk;
	}
}
