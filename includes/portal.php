<?php
require_once('view.php');
class portal extends view{
	function __construct($sql=''){
		parent::__construct($sql);
		$this->add_class('list');
		//$this->add_class('sortable');
		//$this->set_row_action("\"row_clicked('\$id', '\$pk', '\$table')\";");
		$this->set_id($this->id.'_portal');
	}
}
?>