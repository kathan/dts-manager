<?php

require_once('includes/App.php');
require_once('Template.php');

$t = new Template();
echo $t->fetch(App::getTempDir().'loggedin.tpl');