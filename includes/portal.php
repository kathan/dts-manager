<?php
require_once('view.php');
class portal extends view{
	function __construct($sql='', $binds=null){
		parent::__construct($sql, $binds);
		$this->add_class('list');
		$this->set_id($this->id.'_portal');
	}
}
?>