<?php
require_once('Template.php');
require_once('includes/overdue_lib.php');
$GLOBALS['page_title'] = 'Overdue Events';
$t = new Template();
$t->assign('o', get_overdue());
$t->assign('a', get_approaching());
echo $t->fetch(App::$temp.'overdue.tpl');
?>