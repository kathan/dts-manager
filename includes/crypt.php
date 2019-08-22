<?php
class Crypt{
	function encrypt($plain_text, $key){
		$nonce = random_bytes(24); /* Never repeat this! */
		//echo "encrypt nonce: $nonce<br>";
    $nonce_ciphertext = base64_encode($nonce).'.'. base64_encode(sodium_crypto_secretbox($plain_text, $nonce, $key));
    
	  return $nonce_ciphertext;
	}
	
	function decrypt($nonce_ciphertext, $key){
	  
	  $ary = explode('.', $nonce_ciphertext);
	  $nonce = base64_decode($ary[0]);
	  if(isset($ary[1])){
  	  $ciphertext = base64_decode($ary[1]);
		  $plain_text = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
		  
		  return $plain_text;
		}
		
	}
}
?>