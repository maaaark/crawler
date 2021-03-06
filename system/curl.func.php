<?php

function get_useragent(){
   $agent = "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36";
   return $agent;
}

function curl_file($url, $use_useragent = false){
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_HEADER, false);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,5); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	if($use_useragent){
   	curl_setopt($ch, CURLOPT_USERAGENT, get_useragent());
	}
   
   $result = curl_exec($ch);
   $info   = curl_getinfo($ch);
   $error  = curl_error($ch);
   
   return array("result" => $result, "info" => $info, "error" => $error);
}

function check_curl($array){
    if(isset($array["info"]) && isset($array["info"]["http_code"]) && $array["info"]["http_code"] == 200 && isset($array["result"]) && trim($array["result"]) != ""){
        return true;
    }
    return false;
}

?>
