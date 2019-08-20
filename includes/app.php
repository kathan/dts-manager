<?php
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
date_default_timezone_set('America/Chicago');
//==========App Settings===========
define('SITE_NAME', 'DTS');
define('APP_ROOT', '/dts');
define('HTTP_ROOT', "$_SERVER[HTTP_HOST]".APP_ROOT);
define('IMG_ROOT', HTTP_ROOT."/images");
define('CGI_ROOT', "$_SERVER[HTTP_HOST]/cgi-bin");
define('PRIVATE_ROOT', "$_SERVER[HTTP_HOST]/members");
ini_set('include_path', ini_get('include_path').":".App::getAppRoot()."/lib/:..");
//=================================
ini_set('include_path', ini_get('include_path').":".App::getAppRoot()."/includes/:..");

require_once('DB.php');

class App{
    public static $db;
    public static $templates_dir = '/templates/';
    public static $img_dir = '/images';
    public static function getTempDir(){
        return self::getAppRoot().self::$templates_dir;
    }
    
    public static function getImgRoot(){
        return self::getAppRoot().self::$img_dir;
    }
    
    public static function getAppRoot(){
        return pathinfo(__DIR__, PATHINFO_DIRNAME);
    }

    public static function get_username($user_id){
        $sql = "SELECT username
		FROM users
		WHERE user_id = $user_id";
        $re = DB::query($sql);
	if(DB::num_rows($re) > 0){
            $r = DB::fetch_assoc($re);
            return $r['username'];
            }
    }
	
    public static function dbConnect(){
	if(!isset(self::$db)){
            self::$db = mysqli_init();
            $result = DB::connect($_ENV['RDS_USERNAME'], $_ENV['RDS_PASSWORD'], $_ENV['RDS_DB_NAME'], $_ENV['RDS_HOSTNAME']);
            if($result){
                echo "connected";
                if(!DB::query( "SET NAMES 'utf8'")){
                    return false;
		}
            }else{
		echo self::$db->error;
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
?>