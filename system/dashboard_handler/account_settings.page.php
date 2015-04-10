<?php

if(isset($_POST["password1"]) && isset($_POST["password2"]) && isset($_POST["current_pw"])){
   $status = true;
   if($_POST["password1"] != $_POST["password2"]){
      $status = false;
      addInstantMessage("Die beiden Passw&ouml;rter stimmen nicht &uuml;berein.", "red");
   }
   
   if(strlen($_POST["password1"]) <=3){
      addInstantMessage("Das Passwort muss mindestens 4 Zeichen lang sein.", "red");
   }
   
   $user = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT * FROM users WHERE id = '".$_SESSION["user_id"]."'"));
   if(isset($user["id"]) && isset($user["password"]) && isset($user["salt"])){
      $check_pw = md5(trim($_POST["current_pw"]).$user["salt"]);
      
      if(trim(strtolower($check_pw)) != trim(strtolower($user["password"]))){
         $status = false;
         addInstantMessage("Das angegebene \"aktuelle Passwort\" stimmt nicht &uuml;berein.", "red");
      }
   } else {
      $status = false;
   }
   
   if($status){
      $new_pw = md5(trim($_POST["password1"]).$user["salt"]);
      $update = $GLOBALS["db"]->query("UPDATE users SET password = '".$GLOBALS["db"]->real_escape_string($new_pw)."' WHERE id = '".$_SESSION["user_id"]."'");
      addInstantMessage("Account Einstellungen wurden erfolgreich bearbeitet.", "green");
   }
   header("Location: index.php?account_settings");
} else {
   $template = new Template;
   $template->load("account/account_settings");
   $template->assign("SITE_TITLE", "Account Einstellungen");
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}