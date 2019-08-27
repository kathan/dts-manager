<?php
require_once "../includes/app.php";
require_once "../includes/portal.php";
echo "
	<script src='js/base.js'></script>
	<script>
		include('js/sortable.js');
		function row_clicked(id, pk, name){
			window.location=\"?action=Edit&page=\"+name+\"&\"+pk+\"=\"+id;
		}
	</script>";
	$q = new portal();
	$q->set_sql("	SELECT 	load_id,
								(SELECT CONCAT(w.state,' ',w.city) FROM warehouse w WHERE w.warehouse_id = load.origin) origin,
								(SELECT CONCAT(w.state,' ',w.city) FROM warehouse w WHERE w.warehouse_id = load.dest) dest
						FROM `load`");
	$q->set_table('load');
	$q->set_primary_key('load_id');
	
echo $q->render();
?>