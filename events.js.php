<?php
require_once('includes/app.php');
echo "function row_clicked(id, pk, name)
{
	//window.alert(name);
	window.location=\"http://".HTTP_ROOT."/?action=Edit&page=\"+name+\"&\"+pk+\"=\"+id
}";
?>
