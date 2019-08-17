<?php

class App{
	public static connect(){
		$db = pg_connect("host=$_ENV[RDS_HOSTNAME] port=$_ENV[RDS_PORT] dbname=$_ENV[RDS_DB_NAME] user=$_ENV[RDS_USERNAME] password=$_ENV[RDS_PASSWORD]");
		if($db){
			echo "Success";
		}else{
			echo "Failed";
		}
	}
}