<?php
class Request{
	public static $recycled=[];
	public static function get_action(){
		if(isset($_REQUEST['action'])){
			return $_REQUEST['action'];
		}else{
			return '';
		}
	}
	public static function setcookie($name, $value, $expire= 0 , $path='', $domain='' , $secure= false, $httponly= false){
		return setcookie($name , $value, $expire, $path, $domain, $secure, $httponly);
		$_COOKIE[$name] = $value;
	}

	public static function set_recycle($key, $val){
		isset($val) ? Request::$recycled[$key] = $val : '';
	}
	
	public static function recycle_post(){
		require_once('hidden_input.php');
		$rec_vals = array_keys(Request::$recycled);
		$c='';
		foreach($rec_vals as $r){
			$h = new hidden_input($r, Request::$recycled[$r]);
			$c .= $h->render();
		}
		return $c;
	}
	
	public static function recycle_get($exclude=''){
		$rec_vals = array_keys(Request::$recycled);
		$c='';
		foreach($rec_vals as $r){
			($c != '' ?	$c .= '&amp;':'');
			$r != '' ? $c .= "$r=".Request::$recycled[$r] : '';
		}
		return $c;
	}
	
	public static function &safe_get(&$v){
		if(isset($v)){
			return $v;
		}else{
			return '';
		}
	}
	
	public static function get($key){
		return Request::safe_get($_REQUEST[$key]);
	}
	
	public static function is_post(){
		return (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST');
	}
	
	public static function is_get(){
		return (strtoupper($_SERVER['REQUEST_METHOD']) == 'GET');
	}
}