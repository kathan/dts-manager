<?php

class App{
	public static function connect(){
            $db = new mysqli ($_ENV['RDS_HOSTNAME'], $_ENV['RDS_USERNAME'], $_ENV['RDS_PASSWORD'], $_ENV['RDS_DB_NAME']);
            if($db){
			echo "Success";
		}else{
			echo "Failed";
		}
	}
}