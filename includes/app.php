<?php
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);
//error_reporting(E_ALL);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
date_default_timezone_set('America/Chicago');
//==========App Settings===========
//=================================
ini_set('include_path', ini_get('include_path').":".App::getAppRoot()."/includes/:..");

require_once('DB.php');

class App{
    public static $db;
    public static $templates_dir = '/templates/';
    public static $img_dir = 'images';
    public static $site_name = 'DTS';
    
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