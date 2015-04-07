<?php

if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password2"])){
   $status = true;
   if(strlen(trim($_POST["username"])) < 4){
      addInstantMessage("Der Benutzername muss mindestens 4 Zeichen lang sein.");
      $status = false;
   }
   
   if($_POST["password"] != $_POST["password2"]){
      addInstantMessage("Die beiden Passw&ouml;rter stimmen nicht &uuml;berein.");
      $status = false;
   }
   
   $check = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT * FROM users WHERE username = '".$GLOBALS["db"]->real_escape_string(trim($_POST["username"]))."'"));
   if(isset($check["id"]) && $check["id"] > 0){
      addInstantMessage("Es gibt bereits einen Benutzer mit diesem Benutzernamen.");
      $status = false;
   }
   
   if($status){
      $roles = array("SUPERADMIN");
      $salt  = randomString(10);
      $GLOBALS["db"]->query("INSERT INTO users SET username = '".$GLOBALS["db"]->real_escape_string(trim($_POST["username"]))."',
                                                   password = '".$GLOBALS["db"]->real_escape_string(md5(trim($_POST["password"]).$salt))."',
                                                   salt     = '".$GLOBALS["db"]->real_escape_string($salt)."',
                                                   status   = '1',
                                                   roles    = '".$GLOBALS["db"]->real_escape_string(json_encode($roles))."'");
      
      addInstantMessage("Der Admin-Account wurde erfolgreich erstellt.");
   }
   header("Location: ?setAdmin");
} else {
   $template = new template;
   $template->load("set_admin");

   $tmpl = $template->display();
   echo $tmpl;
}