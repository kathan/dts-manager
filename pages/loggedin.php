<?php

require_once("./includes/app.php");
require_once('Template.php');

$t = new Template();
echo $t->fetch(App::getTempDir().'loggedin.tpl');