<?php
    require_once('includes/global.php');
    require_once('includes/auth.php');
    global $feedback;
	
    if (isset($_POST['submit'])){
        if (Auth::login($_POST['username'], $_POST['password'])){	
            /*=================================
            |When the user successfully logs in,
            |forward him to the loggedin.php page
            |so the current cookie state is used.
            =================================*/
            //header("Location: ?page=loggedin");
	}else{
            logError("not logged in", "login");
            loginform();
        }
    }else{
        if(!Auth::loggedIn()){
            echo loginForm();
	}
    }
    echo $feedback;
    
function loginForm(){
    $t = new Template();
    $t->assign('imgRoot', App::getImgRoot());
    return $t->fetch(App::getTempDir().'/login.tpl');
}