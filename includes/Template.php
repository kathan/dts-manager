<?php
require 'vendor/autoload.php';
use Smarty\Smarty;

class Template extends Smarty{
	function __construct(){
		$this->error_reporting = E_ALL & ~E_NOTICE;
		parent::__construct();
	}
}
?>
