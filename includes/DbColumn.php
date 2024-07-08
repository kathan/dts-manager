<?php
class DbColumn{
	protected $table;
	protected $name;
	protected $length;
	protected $type;
	protected $primary;
	protected $unique;
	protected $auto_increment;
	public $error_str;
	
	function __construct(&$table, $name, $type, $options=[]){
		$this->table = $table;
		$this->name = $name;
		$this->type = $type;
		if(array_key_exists('primary', $options)){
			$this->primary = $options['primary'];
		}
		if(array_key_exists('unique', $options)){
			$this->unique = $options['unique'];
		}
		if(array_key_exists('auto_increment', $options)){
			$this->unique = $options['auto_increment'];
		}
	}
	
	function setName($name){
		$this->name = $name;
	}

	function getName(){
		return $this->name;
	}
	
	function setLength($length){
		$this->length = $length;
	}

	function setType($type){
		$this->type = $type;
	}

	function getType(){
		return $this->type;
	}
	
	function setTable($table){
		$this->table - $table;
	}

	function getTable(){
		return $this->table;
	}
	
	function setPrimary(){
		$this->primary = true;
	}
	
	function isPrimary(){
		return $this->primary;
	}

	function setUnique(){
		$this->unique = true;
	}
	
	function isUnique(){
		return $this->unique;
	}

	function setAutoIncrement(){
		$this->auto_increment = true;
	}
	
	function isAutoIncrement(){
		return $this->auto_increment;
	}
}
?>