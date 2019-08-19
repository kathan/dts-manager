<?php
define('APP_ROOT', '/dts');
define('HTTP_ROOT', "$_SERVER[HTTP_HOST]".APP_ROOT);
echo "function row_clicked(id, pk, name)
{
	//window.alert('id:'+id+' pk:'+pk+' name:'+ name);
	window.location=\"http://".HTTP_ROOT."/?action=Edit&page=\"+name+\"&\"+pk+\"=\"+id;
}";
?>