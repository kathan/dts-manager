<?php
require_once('includes/global.php');
require_once('includes/auth.php');

if (Auth::loggedInAs(safe_get($_COOKIE[Auth::COOKIE_USERNAME]))){
    if(logout()){
	echo "You have been logged out.";
    }
}else{
    echo "You have been logged out.";
}
