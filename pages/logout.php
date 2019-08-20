<?php
require_once('includes/global.php');
require_once('includes/auth.php');


if (logged_in_as(safe_get($_COOKIE[COOKIE_USERNAME]))){
	if(logout()){
		echo "You have been logged out.";
	}
}else{
	echo "You have been logged out.";
}


?>
