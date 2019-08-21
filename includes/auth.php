<?php
require_once("global.php");

define('COOKIE_HASH', 'dts_hash');
define('COOKIE_USERNAME', 'dts_username');
define('COOKIE_NAME', 'dts_cookie');
define('PRIVATE_KEY', 'bllop');
define('MIN_PW_LENGTH', 6);
define('MAX_PW_LENGTH', 15);
define('MIN_UN_LENGTH', 4);
define('MAX_UN_LENGTH', 15);
define('PHP_COOKIE_LENGTH', 60*60*24*7);//7 days
define('MYSQL_COOKIE_LENGTH', 'INTERVAL 7 day');

set_cookie();
function logged_in_as($name){
	global $feedback;
        
	if ($name && isset($_COOKIE[COOKIE_HASH])){
            $binds = [$name, $_COOKIE[COOKIE_HASH],$name, $_COOKIE[COOKIE_HASH]];
            $sql = "	
				SELECT u.username
				FROM users u
				WHERE username = ?
				AND hash = ?
				AND hash_expires > NOW()
				AND u.active = 1
				UNION
				SELECT u.username
				FROM users u , user_group ug, groups g
				WHERE g.group_name = ?
				AND u.hash = ?
				AND ug.group_id = g.group_id
				AND u.user_id = ug.user_id
				AND u.hash_expires > NOW()
				AND u.active = 1";
		$result = DB::query($sql, $binds);
		if(DB::error()){
			global $feedback;
			$feedback .= DB::error()."<br>";
			$feedback .= $sql;
			
		}
		if ($result){
			if(DB::numrows($result) < 1){
				return false;
			} else {
				return true;
			}
		}else{
			$feedback .= DB::error();
			return false;
		}
	}else{
		return false;
	}
}

function login($username, $password){
	global $feedback;
	
	if (!$username || !$password){
		$feedback .=  ' ERROR - Missing user name or password ';
		return false;
	} else {
                $binds = [$username,$password];
		$sql= "	SELECT count(*) user_count
			FROM users
			WHERE username = ?
			AND password = ?
			AND active = 1";
		$result = DB::query($sql, $binds);
		if(DB::error()){
			global $feedback;
			$feedback .= DB::error()."<br>";
			$feedback .= $sql."<br>";
		}
                $r = DB::fetch_array($result);
		if (!$result || $r['user_count'] < 1){
			$feedback .=  ' ERROR - User not found or password incorrect ';
			return false;
		} else {
                         $binds = [$username];
			$sql = "	UPDATE users
				SET last_login = NOW()
                                 WHERE user_id = ?";
			$r = DB::query($sql, $binds);
			
			if(DB::error()){
			  $feedback .= DB::error();
			}
			user_set_tokens($username);
			$feedback .=  'You Are Now Logged In ';
			define('LOGGED_IN', true);
			return true;
		}
	}

}

function logged_in(){
	global $feedback;
	
	if (isset($_COOKIE[COOKIE_HASH])){
                $binds = [$_COOKIE[COOKIE_USERNAME], $_COOKIE[COOKIE_HASH]];
		$sql= "	SELECT *
				FROM users
				WHERE username = ?
				AND hash = ?
				AND hash_expires > NOW()
				AND active = 1";
		$result=DB::query($sql);
		if (!isset($result) || $result->num_rows < 1){
			return false;
		} else {
			return true;
		}
	}else{
		return false;
	}
}

function getHash($username){
    return md5($username . time() . PRIVATE_KEY);
}

function logout(){	
    $expires = time() + PHP_COOKIE_LENGTH;
	
    if(setcookie(COOKIE_USERNAME,'',$expires,App::getAppRoot(),'',0) && setcookie(COOKIE_HASH,'',$expires,App::getAppRoot(),'',0)){
	define('LOGGED_IN', false);
	return true;
    }else{
        echo "error";
	return false;
    }
}

function user_set_tokens($username){
    if (!$username){
	$feedback .=  ' ERROR - User Name Missing When Setting Tokens ';
	return false;
    }
    $username=strtolower($username);
    $id_hash= getHash($username);
	
    $expires = time()+PHP_COOKIE_LENGTH;
    setcookie(COOKIE_USERNAME,$username, $expires, App::getAppRoot(), '', 0);
    setcookie(COOKIE_HASH, $id_hash, $expires, App::getAppRoot(), '', 0);
    $binds = [$id_hash, $username];
    $sql = "	UPDATE users
		SET hash = ?,
		hash_expires = ADDDATE(NOW(),
		".MYSQL_COOKIE_LENGTH.")
		WHERE username = ?";
    DB::query($sql, $binds);	
}

function get_user_ID(){
    $binds = [$_COOKIE[COOKIE_USERNAME]];
    $sql = "	SELECT *
		FROM users
		WHERE username = ?";
    $result = DB::query($sql, $binds);
    if(DB::error()){
	global $feedback;
	$feedback .= DB::error()."<br>";
	$feedback .= $sql;
    }

    if ($result && DB::numrows($result) > 0){
	return DB::result($result,0,'user_id');
    } else {
	return false;
    }
}

function user_getrealname(){
    global $G_USER_RESULT;
    //see if we have already fetched this user from the db, if not, fetch it
    if (!$G_USER_RESULT){
        $binds = [user_getname()];
        $sql = "    SELECT *
                    FROM users 
                    WHERE username = ?";
	$G_USER_RESULT = DB::query($sql, $binds);
    }
    if ($G_USER_RESULT && DB::numrows($G_USER_RESULT) > 0){
	return DB::result($G_USER_RESULT,0,'real_name');
    } else {
	return false;
    }
}

//?
function user_getemail(){
	global $G_USER_RESULT;
	//see if we have already fetched this user from the db, if not, fetch it
	if (!$G_USER_RESULT){
            $binds = [user_getname()];
            $sql = "    SELECT *
                        FROM users 
                        WHERE username = ?";
            $G_USER_RESULT=DB::query($sql, $binds);
	}
	if ($G_USER_RESULT && DB::numrows($G_USER_RESULT) > 0){
		return DB::result($G_USER_RESULT,0,'email');
	} else {
		return false;
	}
}

function getUserName(){
    if (logged_in($_COOKIE[COOKIE_USERNAME], $_COOKIE[COOKIE_HASH])){
	return $_COOKIE[COOKIE_USERNAME];
    } else {
	//look up the user some day when we need it
	return ' ERROR - Not Logged In ';
    }
}

function set_cookie(){
    session_start();
    $expires = time() + PHP_COOKIE_LENGTH;
    $new_cookie = session_id();
    setcookie(COOKIE_NAME, $new_cookie, $expires, App::getAppRoot(),'',0);
    $_COOKIE[COOKIE_NAME] = $new_cookie;
}

function debug($s){
    echo $s."<br>";
}
?>