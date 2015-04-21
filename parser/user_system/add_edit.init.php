<?php

if(isset($_GET["edit"]) && $_GET["edit"] > 0){
   $data = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT * FROM users WHERE id = '".$GLOBALS["db"]->real_escape_string($_GET["edit"])."'"));
   if(isset($data["id"]) && $data["id"] > 0){
      if(isset($_POST["id"]) && $_POST["id"] == $data["id"]){
         // Rollen
         if(strpos($_POST["roles"], ",") > 0){
            $roles = explode(",", $_POST["roles"]);
            for($i = 0; $i < count($roles); $i++){
               $roles[$i] = trim(strtoupper($roles[$i]));
            }
         } else {
            $roles = array($_POST["roles"]);
         }
         $roles_json = json_encode($roles);
         
         // Passwort
         $new_password = $data["password"];
         if(isset($_POST["password"]) && isset($_POST["password2"])){
            if(trim($_POST["password"]) != ""){
               if($_POST["password"] != $_POST["password2"] || strlen($_POST["password"]) < 4){
                  addInstantMessage("Das Passwort des Benutzers wurde nicht ge&auml;ndert (die PWs m&uuml;ssen gleich und l&auml;nger als 4 Zeichen sein).", "orange");
               } else {
                  $new_password = md5(trim($_POST["password"]).$data["salt"]);
               }
            }
         }
         
         // Speichern
         $sql = "UPDATE users SET username = '".$GLOBALS["db"]->real_escape_string(trim($_POST["username"]))."',
                                  roles    = '".$GLOBALS["db"]->real_escape_string($roles_json)."',
                                  password = '".$GLOBALS["db"]->real_escape_string($new_password)."'
                            WHERE id       = '".$GLOBALS["db"]->real_escape_string($data["id"])."'";
         
         $GLOBALS["db"]->query($sql);
         addInstantMessage("Der Benutzer wurde erfolgreich bearbeitet.", "green");
         header("Location: index.php?module=user_system");
      } else {
         $template = new template;
         $template->load("edit");
         foreach($data as $column => $value){
            $template->assign(strtoupper($column), $value);
         }
         
         $roles_transform = "";
         $roles_arr       = json_decode($data["roles"], true);
         foreach($roles_arr as $role){
            if(trim($roles_transform) != ""){
               $roles_transform .= ", ";
            }
            $roles_transform .= $role;
         }
         $template->assign("ROLES_TRANSFORM", $roles_transform);
         
         $template->assign("SITE_TITLE", "Benutzer bearbeiten");
         $tmpl = $template->display(true);
         $tmpl = $template->operators();
         echo $tmpl;
      }
   } else {
      error_404();
   }
} elseif(isset($_GET["add"])){
   if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password2"])){
      $status = true;
      if(strlen(trim($_POST["username"])) < 4){
         addInstantMessage("Der Benutzername muss mindestens 4 Zeichen lang sein.", "red");
         $status = false;
      }
      
      if($_POST["password"] != $_POST["password2"] || strlen($_POST["password"]) < 4){
         addInstantMessage("Die beiden Passw&ouml;rter stimmen nicht &uuml;berein.", "red");
         $status = false;
      }
      
      $check = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT * FROM users WHERE username = '".$GLOBALS["db"]->real_escape_string(trim($_POST["username"]))."'"));
      if(isset($check["id"]) && $check["id"] > 0){
         addInstantMessage("Es gibt bereits einen Benutzer mit diesem Benutzernamen.", "red");
         $status = false;
      }
      
      if($status){
         $roles = array("NORMAL_USER");
         if(isset($_POST["roles"]) && trim($_POST["roles"]) != ""){
            if(strpos($_POST["roles"], ",") > 0){
               $roles = explode(",", $_POST["roles"]);
               for($i = 0; $i < count($roles); $i++){
                  $roles[$i] = trim(strtoupper($roles[$i]));
               }
            } else {
               $roles = array($_POST["roles"]);
            }
         }
         $roles_json = json_encode($roles);
         
         $salt  = randomString(10);
         $GLOBALS["db"]->query("INSERT INTO users SET username = '".$GLOBALS["db"]->real_escape_string(trim($_POST["username"]))."',
                                                      password = '".$GLOBALS["db"]->real_escape_string(md5(trim($_POST["password"]).$salt))."',
                                                      salt     = '".$GLOBALS["db"]->real_escape_string($salt)."',
                                                      status   = '1',
                                                      roles    = '".$GLOBALS["db"]->real_escape_string($roles_json)."'");
         
         addInstantMessage("Der Admin-Account wurde erfolgreich erstellt.", "green");
         header("Location: index.php?module=user_system");
      } else {
         header("Location: index.php?module=user_system&add_edit&add");
      }
   } else {
      $template = new template;
      $template->load("create");
      $template->assign("SITE_TITLE", "Neuen Benutzer anlegen");
      $tmpl = $template->display(true);
      $tmpl = $template->operators();
      echo $tmpl;
   }
} else {
   error_404();
}