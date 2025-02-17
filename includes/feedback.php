<?php

class Feedback{
	public static $feedback = [];
	public static $contextual = [];
	public static $error = [];
	public static $context = 'global';
	
	public static function add_feedback($f, $context=null){
		if(!isset($context)){
			$context = self::$context;
		}
		!isset(self::$feedback[$context]) ? self::$feedback[$context] = [] : '';
		self::$feedback[$context][] = $f;
	}
	
	public static function add($msgs, $context=null){
		if(is_array($msgs)){
			foreach($msgs as $msg){
				self::add_feedback($msg, $context);
			}
		}else{
			self::add_feedback($msgs, $context);
		}
	}
	public static function set_context($c){
		self::$context = $c;
	}
	
	public static function reset_context(){
		self::$context = 'global';
	}
	
	public static function add_error($msg, $file, $function){
		$e = new FBError($msg, $file, $function);
		self::$error[] = $e;
	}
	public static function show($context='global'){
		return self::show_feedback($context);
	}

	public static function get($context='global'){
		return self::show_feedback($context);
	}

	public static function show_feedback($context='global'){
		$all_feedback = "";
		if(isset(self::$feedback[$context])){
			$fb = self::$feedback[$context];
		}else{
			$fb = [];
		}
		
		foreach($fb as $f){
			if($f != ''){
				$all_feedback .= $f."<br />";
			}
		}
		return $all_feedback;
	}
	
	public static function show_errors(){
		$c = '<table>
				<tr>
					<td>Message</td><td>File</td><td>Function</td>
				</tr>';
		foreach(self::$error as $err){
			$c .= "<td>$err->msg</td><td>$err->file</td><td>$err->function</td>";
		}
		$c .= '</table>';
		return $c;
	}
}

class FBError{
	var $msg;
	var $file;
	var $function;
	function error($msg, $file, $function){
		$this->msg = $msg;
		$this->file = $file;
		$this->function = $function;
		
	}
}
?>