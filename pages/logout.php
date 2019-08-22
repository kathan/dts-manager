<?php
require_once('includes/global.php');
require_once('includes/auth.php');

if (Auth::loggedInAs(safe_get($_COOKIE[Auth::COOKIE_USERNAME]))){
    if(Auth::logout()){
        header("Location: ?page=login");
    }
}else{
    Feedback::add("You have been logged out.");
}
