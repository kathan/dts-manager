<?php

class DB{
    public static $db;
    public static function connect($dbuser, $dbpass, $dbname='', $dbhost='localhost'){
	if(!isset(self::$db)){
            self::$db = new mysqli ($dbhost, $dbuser, $dbpass, $dbname);
            if(!self::$db){
                return false;
            }
	}
		
	return self::$db;
    }
	
    public static function close(){
        self::$db->close();
    }
	
    public static function query($sql, $binds=null){
        $bind_ary = [];
        $bind_ary[0] = '';
        $stmt = self::$db->prepare($sql);
	if(isset($binds)){
            
            foreach($binds as $val){
                switch(gettype($val)){
                    case 'string':
                        $bind_ary[0] .= 's';
                        $bind_ary[] = &$val;
                        break;
                    case 'integer':
                        $bind_ary[0] .= 'i';
                        $bind_ary[] = &$val;
                        break;
                    case 'double':
                        $bind_ary[0] .= 'd';
                        $bind_ary[] = &$val;
                        break;
                }
            }
            $stmt->bind_para
            call_user_func_array([$stmt, 'bind_param'], $bind_ary);
        }
        return $stmt->execute();
    }
	
    public static function num_rows(mysqli_result $result){
        if (isset($result)){
            return $result->num_rows;
        }
    	return 0;
    }
	
    public static function numrows($result){
        return self::num_rows($result);
    }

    public static function result($result, $row, $field){
        $result->data_seek($row);
        $row_obj = $result->fetch_row();
        return $row_obj[$field];
    }

    public static function data_seek($result, $offset){
        return $result->data_seek($offset);
    }

    public static function affected_rows(){
        return self::$db->affected_rows;
    }
	
    public static function fetch_array($result){
        return $result->fetch_array();
    }

    public static function fetch_assoc($result){
        return $result->fetch_assoc();
    }
    
    public static function insertid(){
        return self::$db->insert_id;
    }
	
    public static function last_insert_id(){
        return self::insertid();
    }

    public static function error(){
        if(self::$db->error){
            return self::$db->error;
        }
    }

    public static function current_db(){
	$sql = "SELECT database()";
	$re = DB::query($sql);
	$row = DB::fetch_array($result);
	return $row[0];
    }
	
    public static function form_to_db($s){
        return self::$db->real_escape_string($s);
    }

    public static function db_date($year, $month, $day){
        return $year.'-'. str_pad ($month, 2, '0', STR_PAD_LEFT).'-'.str_pad ($day, 2, '0', STR_PAD_LEFT);
    }
	
    public static function date_to_mySQL($origdate=null){
        if(isset($origdate)){
            return date("Y-m-d H:i:s", strtotime($origdate));
        }
        return date("Y-m-d H:i:s");
    }
	
    public static function esc($str){
        return self::$db->real_escape_string($str);
    }
	
    public static function to_array($re, $single=false){
        if($single){
            return DB::fetch_assoc($re);
		}
        $ary = [];
	
        while($row = DB::fetch_assoc($re)){
            $ary[] = $row;
        }
        return $ary;
		
    }
}

?>