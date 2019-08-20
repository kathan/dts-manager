<?php
    /*=================================
    |
    |
    |
    =================================*/
    require_once('includes/global.php');
    require_once('includes/auth.php');
    global $feedback;
	
    if (isset($_POST['submit'])){
        if (login($_POST['username'], $_POST['password'])){	
            /*=================================
            |When the user successfully logs in,
            |forward him to the loggedin.php page
            |so the current cookie state is used.
            =================================*/
	}else{
            logError("not logged in", "login");
            loginform();
        }
    }else{
        if(!logged_In()){
            echo loginForm();
	}
    }
    echo $feedback;
    
function loginForm(){
	$content = "
	<img src='".App::getImgRoot()."/dts.gif'>
	<table class='center'>
		<th colspan=2>Login</th>
		<FORM ACTION='' METHOD='POST' target='_top'>
		<input type='hidden' name='referer' value='".safe_get($_SERVER['HTTP_REFERER'])."'>
		<tr>
			<td class='lblstyle'>
				User Name:
			</td>
			<td class='editstyle'>
				<INPUT TYPE='TEXT' NAME='username' VALUE='".safe_get($_COOKIE[COOKIE_USERNAME])."' SIZE='10' MAXLENGTH='15'>
			</td>
		</tr>
		<tr>
			<td class='lblstyle'>
				Password:
			</td>
			<td class='editstyle'>
				<INPUT TYPE='password' NAME='password' SIZE='10' MAXLENGTH='15'>
			</td>
		</tr>
		<tr>
			<td class='editstyle'></td>
			<td class='editstyle'>
				<INPUT TYPE='SUBMIT' NAME='submit' VALUE='Login'>
			</td>
		</tr>
		</FORM>
	</table>
";
return $content;
}	

?>
