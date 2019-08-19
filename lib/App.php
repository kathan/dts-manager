<?php

class App{
	public static function connect(){
            $db = new mysqli ($_ENV['RDS_HOSTNAME'], $_ENV['RDS_USERNAME'], $_ENV['RDS_PASSWORD'], $_ENV['RDS_DB_NAME']);
//            $db = pg_connect("host=$_ENV[RDS_HOSTNAME] port=$_ENV[RDS_PORT] dbname=$_ENV[RDS_DB_NAME] user=$_ENV[RDS_USERNAME] password=$_ENV[RDS_PASSWORD]");
            if($db){
			echo "Success";
		}else{
			echo "Failed";
		}
	}
}