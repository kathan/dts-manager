<?php
require_once("html_form.php");

class delete_button extends html_form
{
	var $id;
	var $primary_key;
	function __construct($action, $id, $primary_key){
		$this->create_hidden_input('action', 'delete');
		$this->create_hidden_input($primary_key, $id);
		$this->create_hidden_input('pk', $primary_key);
		$this->create_submit_input('Delete');
		parent::__construct($action);
		$this->in_table = false;
	}
}
?>