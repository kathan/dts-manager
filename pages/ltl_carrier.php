<?php
require_once('includes/DB_Editor.php');
$t = new DB_Editor('ltl_carrier');
echo $t->execute();
?>