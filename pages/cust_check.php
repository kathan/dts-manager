<?php
require_once('app.php');
$_SERVER['REQUEST_METHOD'] == 'POST' ? check_for_existing($_POST) : '';
function check_for_existing($cust)
{
		$sql = "SELECT c.*, '$cust[address]' REGEXP '^[0-9]+', address REGEXP '^[0-9]+', soundex(address) 
				FROM customer c
				WHERE (soundex(name) like CONCAT(soundex('$cust[name]'), '%')
				OR soundex('$cust[name]') like CONCAT(soundex(name), '%'))
				AND (soundex(address) like CONCAT(soundex('$cust[address]'), '%')
				OR soundex('$cust[address]') like CONCAT(soundex(address), '%'))
				AND (zip like '$cust[zip]%'
				OR '$cust[zip]' like CONCAT(zip, '%'))";
		$re = DB::query($sql);
		echo DB::error();
		echo "Results<table>";
		while($r = DB::fetch_assoc($re))
		{
			echo "<tr>";
			foreach($r as $i)
			{
				echo "<td>$i</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
}
?>
<form method="post">
	Name: <input type="text" name="name" value="<?=$_POST['name']?>"/><br/>
	Address: <input type="text" name="address" value="<?=$_POST['address']?>"/><br/>
	Zip: <input type="text" name="zip" value="<?=$_POST['zip']?>"/>
	<input type="submit" value="Add" />
</form>