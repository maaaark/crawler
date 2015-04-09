<?php

function checkInstantMessages(){
   if(isset($_SESSION["instant_messages"]) && is_array($_SESSION["instant_messages"])){
      if(count($_SESSION["instant_messages"]) > 0){
         return true;
      }
   }
   return false;
}

function getInstantMessages(){
   $out = "";
   if(isset($_SESSION["instant_messages"]) && is_array($_SESSION["instant_messages"])){
      if(count($_SESSION["instant_messages"]) > 0){
         foreach($_SESSION["instant_messages"] as $message){
            $out .= '<div class="message '.$message[1].'">'.$message[0].'</div>';
         }
      }
   }
   $_SESSION["instant_messages"] = array();
   return $out;
}

function addInstantMessage($message, $type = "normal"){
   $temp = array($message, $type);
   
   if(!isset($_SESSION["instant_messages"]) || !is_array($_SESSION["instant_messages"])){
      $_SESSION["instant_messages"] = array();
   }
   $_SESSION["instant_messages"][] = $temp;
   return true;
}

function format_number($number){
   return number_format($number, 0, ",", ".");
}

function make_seed(){ 
   list($usec , $sec) = explode (' ', microtime()); 
   return (float) $sec + ((float) $usec * 100000); 
} 
function randomString($len) { 
   srand(make_seed());  
   //Der String $possible enthält alle Zeichen, die verwendet werden sollen 
   $possible="ABCDEFGHJKLMNPRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789"; 
   $str=""; 
   while(strlen($str)<$len) { 
     $str.=substr($possible,(rand()%(strlen($possible))),1); 
   } 
   return($str); 
}

function login($user_id){
   $user = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT * FROM users WHERE id = '".$GLOBALS["db"]->real_escape_string($user_id)."'"));
   
   if(isset($user["id"]) && $user["id"] > 0){
      $_SESSION["username"] = $user["username"];
      $_SESSION["user_id"]  = $user["id"];
      $_SESSION["status"]   = $user["status"];
      $_SESSION["roles"]    = json_decode($user["roles"], true);
   }
   return true;
}

function logout($user_id){
   $backupInstantMessages = array();
   if(isset($_SESSION["instant_messages"])){
      $backupInstantMessages = $_SESSION["instant_messages"];
   }
   
   session_destroy();
   session_start();
   $_SESSION["instant_messages"] = $backupInstantMessages;
   return true;
}

function checkCanSee($user_roles, $allowed_roles){
   $return = false;
   foreach($user_roles as $user_role){
      foreach($allowed_roles as $allowed_role){
         if(trim($allowed_role) == trim($user_role)){
            $return = true;
         }
      }
   }
   return $return;
}