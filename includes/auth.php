<?php
require_once("global.php");
require_once("DB_Table.php");
require_once('crypt.php');

class Auth{
    const COOKIE_HASH = 'dts_hash';
    const COOKIE_USERNAME = 'dts_username';
    const COOKIE_NAME = 'dts_cookie';
    const PRIVATE_KEY = 'bllop';
    const MIN_PW_LENGTH = 6;
    const MAX_PW_LENGTH = 15;
    const MIN_UN_LENGTH = 4;
    const MAX_UN_LENGTH = 15;
    const PHP_COOKIE_LENGTH=  60*60*24*7;//7 days
    const MYSQL_COOKIE_LENGTH = 'INTERVAL 7 day';
    private static $crypt_key = 'nKmUfd93vJFb0tNTiHmiT93oazD+i8wULSPDUJWcqUQ=';
    public static $LOGGED_IN = false;

    static function loggedInAs($name){
	    global $feedback;
        
	    if ($name && isset($_COOKIE[self::COOKIE_HASH])){
            $binds = [$name, $_COOKIE[self::COOKIE_HASH], $name, $_COOKIE[self::COOKIE_HASH]];
            $sql = "	
				SELECT u.username
				FROM `users` u
				WHERE `username` = ?
				AND `hash` = ?
				AND `hash_expires` > NOW()
				AND u.active = 1
				UNION
				SELECT u.username
				FROM `users` u , `user_group` ug, `groups` g
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

    static function login($username, $password){
        global $feedback;
        
        if (!$username || !$password){
            $feedback .=  ' ERROR - Missing user name or password ';
            return false;
        } else {
            $binds = [$username, self::encryptPassword($password)];
            $sql= "	SELECT count(*) user_count
                    FROM `users`
                    WHERE `username` = ?
                    AND `hash_password` = ?
                    AND `active` = 1";
            $result = DB::query($sql, $binds);
            if(DB::error()){
                global $feedback;
                $feedback .= DB::error()."<br>";
                $feedback .= $sql."<br>";
            }
            $r = DB::fetch_array($result);
            if (!$result || $r['user_count'] < 1){
                echo "$sql<br>";
                var_dump($binds);
                $feedback .=  ' ERROR - User not found or password incorrect ';
                return false;
            } else {
                $binds = [$username];
                $sql = "	UPDATE `users`
                            SET `last_login` = NOW()
                            WHERE `user_id` = ?";
                $r = DB::query($sql, $binds);
                
                if(DB::error()){
                    $feedback .= DB::error();
                }
                if(self::user_set_tokens($username)){
                    $feedback .=  'You Are Now Logged In ';
                    self::$LOGGED_IN = true;
                    return true;
                }else{
                    $feedback .= "Could not set user tokens";
                    return false;
                }
            }
        }
    }

    static function loggedIn(){
        global $feedback;
        if(self::$LOGGED_IN){
                return true;
            }
            
            if (isset($_COOKIE[self::COOKIE_USERNAME]) && isset($_COOKIE[self::COOKIE_HASH])){
                //Find auth hash
                $binds = [$_COOKIE[self::COOKIE_USERNAME], $_COOKIE[self::COOKIE_HASH]];
                $sql= "	SELECT count(*) user_count
                        FROM `users`
                        WHERE `username` = ?
                        AND `hash` = ?
                        AND `hash_expires` > NOW()
                        AND `active` = 1";
            $result = DB::query($sql, $binds);
            $r = DB::fetch_array($result);
            if (!isset($result) || $r['user_count'] < 1){
                self::logout();
                return false;
            } else {
                self::$LOGGED_IN = true;
                return true;
            }
        }
        return false;
    }

    function getToken(){
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    static function logout(){	
        $expires = time() + self::PHP_COOKIE_LENGTH;
        $cookie_options = [
            'expires' => $expires,
            'domain' => App::getHttpRoot(),
            'samesite' => 'strict',
            'secure' => false
        ];

        unset($_COOKIE[self::COOKIE_USERNAME]);
        unset($_COOKIE[self::COOKIE_HASH]);
        if(setcookie(self::COOKIE_USERNAME, "", $expires, "" , App::getHttpRoot()) &&
            setcookie(self::COOKIE_HASH, '', $expires, "", App::getHttpRoot())){
        //if(setcookie(self::COOKIE_USERNAME, '', $cookie_options) &&
        //    setcookie(self::COOKIE_HASH, '', $cookie_options)){
            self::$LOGGED_IN = false;
            return true;
        }else{
            return false;
        }
    }

    static function user_set_tokens($username){
        if (!$username){
            $feedback .=  ' ERROR - User Name Missing When Setting Tokens ';
            return false;
        }
        $username = strtolower($username);
        $id_hash = self::getToken();
	
        $expires = time()+self::PHP_COOKIE_LENGTH;
        $cookie_options = [
            'expires' => $expires,
            'domain' => App::getHttpRoot(),
            'samesite' => 'strict',
            'secure' => false
        ];
        $_COOKIE[self::COOKIE_USERNAME] = $username;
        $_COOKIE[self::COOKIE_HASH] = $id_hash;
        if(setcookie(self::COOKIE_USERNAME, $username, $expires, "" , App::getHttpRoot()) &&
            setcookie(self::COOKIE_HASH, $id_hash, $expires, "", App::getHttpRoot())){
        // setcookie(self::COOKIE_USERNAME, $username, $cookie_options);
        // setcookie(self::COOKIE_HASH, $id_hash, $cookie_options);
            $t = new DB_Table('users');
            $expires_ts = time() + self::PHP_COOKIE_LENGTH;
            $hash_expires = new DateTime("@$expires_ts");
            $set = [
                'hash' => $id_hash, 
                'hash_expires' => $hash_expires
            ];
            $where = [
                'username' => $username
            ];
            $result = $t->update($set, $where);
            return $result;
        }else{
            $feedback .= "Could not set tokens";
            return false;
        }
    }

    static function getUserId(){
        $binds = [$_COOKIE[self::COOKIE_USERNAME]];
        $sql = "	SELECT user_id
		            FROM `users`
		            WHERE `username` = ?";
        $result = DB::query($sql, $binds);
        if(DB::error()){
            global $feedback;
            $feedback .= DB::error()."<br>";
            $feedback .= $sql;
        }

        if ($result && DB::numrows($result) > 0){
            $user_id = DB::result($result,0,'user_id');
            return $user_id;
        } else {
            return false;
        }
    }

    static function user_getrealname(){
        global $G_USER_RESULT;
        //see if we have already fetched this user from the db, if not, fetch it
        if (!$G_USER_RESULT){
            $binds = [self::user_getname()];
            $sql = "    SELECT *
                        FROM `users` 
                        WHERE `username` = ?";
            $G_USER_RESULT = DB::query($sql, $binds);
        }
        if ($G_USER_RESULT && DB::numrows($G_USER_RESULT) > 0){
            return DB::result($G_USER_RESULT,0,'real_name');
        } else {
            return false;
        }
    }

    static function user_getemail(){
	global $G_USER_RESULT;
	//see if we have already fetched this user from the db, if not, fetch it
	if (!$G_USER_RESULT){
            $binds = [user_getname()];
            $sql = "    SELECT *
                        FROM `users` 
                        WHERE `username` = ?";
            $G_USER_RESULT = DB::query($sql, $binds);
	}
	if ($G_USER_RESULT && DB::numrows($G_USER_RESULT) > 0){
            return DB::result($G_USER_RESULT,0,'email');
	} else {
            return false;
	}
    }

    static function getUserName(){
        if (Auth::loggedIn($_COOKIE[self::COOKIE_USERNAME], $_COOKIE[self::COOKIE_HASH])){
            return $_COOKIE[self::COOKIE_USERNAME];
        } else {
            //look up the user some day when we need it
            return ' ERROR - Not Logged In ';
        }
    }

    static function encryptPassword($password){
        return hash("sha512", $password);
    }

    // static function hashPassword($password){
    //     return hash("sha512", $password);
    // }

    static function debug($s){
        echo $s."<br>";
    }
}
