<?php

if(isset($_POST["username"]) && isset($_POST["password"])){
   $check = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT * FROM users WHERE username = '".$GLOBALS["db"]->real_escape_string($_POST["username"])."'"));
   
   if(isset($check["id"]) && $check["id"] > 0){
      if(md5(trim($_POST["password"]).$check["salt"]) == $check["password"]){
         if($check["status"] == 1){
            addInstantMessage("Erfolgreich eingeloggt.", "green");
            login($check["id"]);
         } else {
            addInstantMessage("Der Account ist gesperrt.", "orange");
         }
      } else {
         addInstantMessage("Login fehlgeschlagen", "red");
      }
   } else {
      addInstantMessage("Login fehlgeschlagen", "red");
   }
   
   header("Location: index.php");
} else {
   $template = new template;
   $template->load("login");
   $template->assign("SITE_TITLE", "Login");
   $tmpl = $template->display(true, "design_login");
   $tmpl = $template->operators();
   echo $tmpl;
}