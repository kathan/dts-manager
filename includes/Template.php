<?php

require_once('smarty-master/libs/SmartyBC.class.php');
class Template extends Smarty{
	function __construct(){
		$this->error_reporting = E_ALL & ~E_NOTICE;
		parent::__construct();
	}
	//$this->error_reporting = 0;
}
?>