<?php
require_once"includes/portal.php";
$binds = [];
$sql ="SELECT * FROM events WHERE event_date >= NOW()";
if(isset($_REQUEST['created_by'])){
    $binds = [$_REQUEST['created_by']];
    $sql .= "WHERE created_by = ?";
}
$p = new portal($sql, $binds);
$p->render();
?>