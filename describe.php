<?php
require_once('includes/database.php');
echo "<table><tr>";
echo "<td><a href='/?action=Query'>Query</a></td>";
echo "</tr></table>";
if($_REQUEST['action'])
{
	switch($_REQUEST['action'])
	{
		case 'describe':
			describe();
			break;
		default:
			query();
			break;
	}
}else{
	query();
}

function describe()
{
	$sql = "Describe `$_REQUEST[table]`";
	$re = mysql_query(stripslashes($sql));
	echo 	"<table><tr><td>Field</td><td>Type</td><td>Key</td><td>Null</td><td>Extra</td></tr>";
	while($r = db_fetch_assoc($re))
	{
		echo "<tr><td>$r[Field]</td><td>$r[Type]</td><td>$r[Key]</td><td>$r[Null]</td><td>$r[Extra]</td></tr>";
	}
	echo "</table>";
}

function query()
{
	echo "
		<form method='post'>
			<textarea name='sql' cols='80' rows='20'>".stripslashes($_REQUEST['sql'])."</textarea>
				<input type='submit' name='action' value='Query'>
		</form>";
	if(isset($_REQUEST['sql']))
	{
		$re = mysql_query(stripslashes($_REQUEST['sql']));
		if(db_error())
		{
			echo db_error();
		}else{
			echo "<table><tr>";
			for ($c=0; $c < mysql_num_fields($re); $c++)
			{
				echo "<th>".mysql_field_name ($re, $c)."</th>";
			}
			echo "</tr>";
			while($r = mysql_fetch_array($re))
			{
				echo "<tr>";
				for ($c=0; $c < mysql_num_fields($re); $c++)
				{
					echo "<td>".$r[$c]."</th>";
				}
				echo "</tr>";
			}
			echo "</table>";
		}
	}
}

?>