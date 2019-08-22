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

    static function refValues($arr){
        if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
        {
            $refs = [];
            foreach($arr as $key => $value){
                $refs[$key] = &$arr[$key];
            }
            return $refs;
        }
        return $arr;
    }

    public static function query($sql, $binds=null){
        $bind_ary = [];
        $bind_ary[0] = '';
        $stmt = self::$db->stmt_init();
        $stmt->prepare($sql);
	    if(isset($binds) && count($binds) > 0){
            foreach($binds as $val){
                switch(gettype($val)){
                    case 'string';
                        $bind_ary[0] = 's'.$bind_ary[0];
                        $bind_ary[] = $val;
                        break;
                    case 'integer':
                        $bind_ary[0] = 's'.$bind_ary[0];
                        $bind_ary[] = $val;
                        break;
                    case 'double':
                        $bind_ary[0] = 'd'.$bind_ary[0];
                        $bind_ary[] = $val;
                        break;
                    case 'object':
                        switch(get_class($val)){
                            case 'DateTime':
                                $bind_ary[0] = 's'.$bind_ary[0];
                                $bind_ary[] = $val->format('Y-m-d H:i:s');
                                break;
                        }
                        break;

                }
            }
            $ref_vals = self::refValues($bind_ary);
            if(!call_user_func_array([$stmt, 'bind_param'], $ref_vals)){
                debug_print_backtrace();
                echo "<br>$sql<br>";
                var_dump($binds);
                return false;
            }
        }
        if($stmt->execute()){
            $result = $stmt->get_result();
            return $result;
        }else{
            return false;
        }
    }
    
    public static function num_fields($result){
        return $result->field_count; 
    }

    public static function field_name($result, $idx){
        $result->field_seek($idx);
        $finfo = $result->fetch_field();
        return $finfo->name;
    }

    public static function num_rows($result){
        if ($result){
            return $result->num_rows;
        }
    	return 0;
    }
	
    public static function numrows($result){
        return self::num_rows($result);
    }

    public static function result($result, $row, $field){
        $result->data_seek($row);
        $row_obj = $result->fetch_assoc();
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