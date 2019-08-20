<?php
echo "function row_clicked(id, pk, name){
	window.location=\"?action=Edit&page=\"+name+\"&\"+pk+\"=\"+id;
}";
?>