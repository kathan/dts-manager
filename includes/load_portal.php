<?php
require_once"app.php";
echo "<script>
		function row_clicked(id, pk, name)
		{
			window.location=\"http://".HTTP_ROOT."/?action=Edit&page=\"+name+\"&\"+pk+\"=\"+id;
		}
	</script>";
	$q = new query("	SELECT 	load_id,
								(SELECT CONCAT(w.state,' ',w.city) FROM warehouse w WHERE w.warehouse_id = load.origin) origin,
								(SELECT CONCAT(w.state,' ',w.city) FROM warehouse w WHERE w.warehouse_id = load.dest) dest
						FROM `load`");
	$q->set_primary_key('load_id');
	$q->set_row_action("\"row_clicked('\$id', '\$pk', 'load')\";");
echo $q->view_list();//*/
?>