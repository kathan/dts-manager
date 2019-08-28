<?php
ini_set('memory_limit', '1024M');
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);
//error_reporting(E_ALL);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
date_default_timezone_set('America/Chicago');
//==========App Settings===========
//=================================
ini_set('include_path', ini_get('include_path').":".App::getAppRoot()."/includes/:..");

require_once('DB.php');

function handleFatal() {
    $error = error_get_last();
    var_dump($error);
    var_dump(debug_backtrace(0));
    if ($error['type'] === E_ERROR) {
        !isset($errno) ? $errno  = $error["type"] : $errno = E_CORE_ERROR;
        !isset($errfile) ? $errfile = $error["file"] : $errfile = "unknown file";
        !isset($errline) ? $errline = $error["line"] : $errline = 0;
        !isset($errstr) ? $errstr  = $error["message"] : $errstr = "unknown";
        // App:logit('Fatal Error: '.$errstr);
        // Feedback::add('Fatal Error: '.$errstr);
        $stack = debug_backtrace(0);
        // App::addError(['type'=>'Fatal Error', 'number'=>$errno, 'message'=>$errstr, 'file'=>$errfile, 'line'=>$errline, 'stack'=>$stack]);
        //mailError('Fatal Error', $stack, $errno, $errstr, $errfile, $errline);
    }/*else{
        mailError('Fatal Error', [], -0, 'Something mysterious happened');
    }*/
	
}
class App{
    public static $db;
    public static $templates_dir = '/templates/';
    public static $img_dir = 'images';
    public static $site_name = 'DTS';
    const DISPLAY_DATE_FORMAT = '%c/%e/%y';

    public static function getSiteName(){
        return self::$site_name;
    }

    public static function getHttpRoot(){
        return "$_SERVER[HTTP_HOST]";
    }
    
    public static function getTempDir(){
        return self::getAppRoot().self::$templates_dir;
    }
    
    public static function getImgRoot(){
        return self::$img_dir;
    }
    
    public static function getAppRoot(){
        return pathinfo(__DIR__, PATHINFO_DIRNAME);
    }

    public static function getUsername($user_id){
        $sql = "SELECT username FROM `users` WHERE user_id = ?";
        $binds = [$user_id];
        $stmt = self::$db->prepare($sql);
        $result = $stmt->execute($binds);
        if(!$result){
            return false;
        }
        return $stmt->fetchColumn();
    }

    public static function dbConnect(){
        $env = getenv();
	    if(!isset(self::$db)){
            self::$db = new PDO("mysql:host=$env[RDS_HOSTNAME];dbname=$env[RDS_DB_NAME];charset=utf8", $env['RDS_USERNAME'], $env['RDS_PASSWORD'], [PDO::MYSQL_ATTR_FOUND_ROWS => true]);
            if(self::$db){
                return true;
            }else{
                Feedback::add(self::$db->errorInfo);
                return false;
            }
        }
	    return true;
    }
	
    public static function init(){
	    if(!self::dbConnect()){
            echo "Could not connect to database.";
	    }
    }
}

App::init();