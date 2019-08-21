<?php
// /etc/local.inc includes the machine specific database connect info
//Modified by Darrel Kathan 11/18/05 for PHP 4

require_once('DB.php');
	
function &db_get_children(&$table_obj, $column=null){
		mysqli_select_db('information_schema');
		$sql = "		SELECT *
						FROM key_column_usage
						WHERE TABLE_SCHEMA = '".DBNAME."'
						AND REFERENCED_TABLE_NAME = '".$table_obj->get_name()."' ";
				
		if($column){
			$sql .= "AND REFERENCED_COLUMN_NAME = '$column'";
		}
		$r= DB::query($sql);
		if(DB::error()){
			echo DB::error();
			echo $sql;
		}
		if($r){
			$i=0;
			$child = [];
			while($row = DB::fetch_assoc($r)){
				//echo "table:".$row['TABLE_NAME']."<br>column:".$row['COLUMN_NAME']."<br>referenced column:".$row['REFERENCED_COLUMN_NAME']."<br><br>";
				$child[$i]['TABLE_NAME'] = $row['TABLE_NAME'];
				$child[$i]['COLUMN_NAME'] = $row['COLUMN_NAME'];
				$child[$i]['REFERENCED_COLUMN_NAME'] = $row['REFERENCED_COLUMN_NAME'];
				$i++;
			}
		}
		mysqli_select_db(DBNAME);
		if(isset($child)){
			return $child;
		}
}

function &db_get_parent(&$table_obj, $column){
	require_once("column.php");
	mysqli_select_db('information_schema');
	$sql = "		SELECT *
					FROM key_column_usage
					WHERE TABLE_SCHEMA = '".DBNAME."'
					AND TABLE_NAME = '".$table_obj->get_name()."' 
					AND REFERENCED_COLUMN_NAME is NOT NULL
					AND COLUMN_NAME = '$column'";
		
	$r= DB::query($sql);
	if(DB::error()){
		echo DB::error();
		echo $sql;
	}
	mysqli_select_db(DBNAME);
	if(DB::num_rows($r)>0){
		$row = DB::fetch_assoc($r);
		
		$t = new table($row['REFERENCED_TABLE_NAME'], true);
		$new_col = new column($t, $row['REFERENCED_COLUMN_NAME'], true);
				
		return $new_col;
	}
}

function db_connect(){
	$conn = mysqli_connect(DBHOST, DBUSER, DBPASS);
	DB::select_db(DBNAME, $conn);//added by DK
	if (!$conn){
		echo mysqli_error();
	}
	return $conn;
}

function db_query($qstring,$print=0){
	$r = DB::query($qstring);
	if(DB::error()){
		echo DB::error();
		echo $qstring;
	}
	return $r;//added by DK
}

function db_numrows($qhandle){
	// return only if qhandle exists, otherwise 0
	if ($qhandle){
		return DB::numrows($qhandle);
	} else{
		return 0;
	}
}

function db_num_rows($qhandle){
	return DB::numrows($qhandle);
}

function db_result($qhandle,$row,$field){
	return DB::result($qhandle,$row,$field);
}

function db_numfields($lhandle){
	return DB::numfields($lhandle);
}

function db_num_fields($lhandle){
	return DB::numfields($lhandle);
}
function db_fieldname($lhandle,$fnumber){
  return DB::fieldname($lhandle,$fnumber);
}

function db_affected_rows($qhandle){
	return DB::affected_rows();
}
	
function db_fetch_array($qhandle){
	return DB::fetch_array($qhandle);
}

function db_fetch_assoc($qhandle){
	return DB::fetch_assoc($qhandle);
}
	
function db_insertid(){
	$sql ="SELECT LAST_INSERT_ID()";
	$result=DB::query($sql);
	$row=DB::fetch_array($result);
	return $row[0];
}

function db_to_array($re, $single=false){
	if(is_resource($re)){
		if($single){
			return DB::fetch_assoc($re);
		}else{
			$ary = [];
	
			while($row = DB::fetch_assoc($re)){
				$ary[] = $row;
			}
			return $ary;
		}
		
	}else{
		return $re;
	}
}
function db_error(){
	if(DB::error()){
		return "\n\n<P><B>".@DB::error()."</B><P>\n\n";
	}
}

function formToDB($s){
		return DB::esc($s);
	}

?>
