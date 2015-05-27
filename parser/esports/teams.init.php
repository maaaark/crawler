<?php

if(isset($_GET["edit_team"])){
   if(isset($_POST["id"])){
      $data = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_team WHERE id = '".$GLOBALS["db_fi"]->real_escape_string($_POST["id"])."'"));
      
      if(isset($data["id"]) && $data["id"] > 0){
         // Altes Bild löschen?
         if(isset($_POST["delete_custom_team_image"]) && $_POST["delete_custom_team_image"] == "true"){
            if(isset($data["custom_logo"]) && trim($data["custom_logo"]) != ""){
               if(defined("FLASHIGNITE_MEDIA_DIR") && trim(FLASHIGNITE_MEDIA_DIR) != ""){
                  if(file_exists(FLASHIGNITE_MEDIA_DIR.trim($data["custom_logo"]))){
                     unlink(FLASHIGNITE_MEDIA_DIR.trim($data["custom_logo"]));
                     $update = $GLOBALS["db_fi"]->query("UPDATE esports_team SET custom_logo = '' WHERE id = '".$data["id"]."'");
                  }
                  addInstantMessage("Das Bild wurde erfolgreich gel&ouml;scht.", "green");
               } else {
                  addInstantMessage("Das Bild konnte nicht gelöscht werden: Konstante FLASHIGNITE_MEDIA_DIR nicht gesetzt.", "red");
               }
            }
         }
         
         // Neues Bild hochladen?
         if(isset($_FILES["custom_image"]) && isset($_FILES["custom_image"]["tmp_name"]) && trim($_FILES["custom_image"]["tmp_name"]) != "" && file_exists($_FILES["custom_image"]["tmp_name"])){
            $dateityp = GetImageSize($_FILES["custom_image"]["tmp_name"]);
            if($dateityp[2] != 0){
               if(defined("FLASHIGNITE_MEDIA_DIR") && trim(FLASHIGNITE_MEDIA_DIR) != ""){
                  $ext = "jpg";
                  if($dateityp["mime"] == "image/png"){
                     $ext = "png";
                  } elseif($dateityp["mime"] == "image/gif"){
                     $ext = "gif";
                  }
                  
                  $file_name = "/uploads/esports/team_".$data["id"]."_".str_replace(" ", "_", strtolower($data["acronym"])).".".$ext;
                  move_uploaded_file($_FILES["custom_image"]["tmp_name"], FLASHIGNITE_MEDIA_DIR.$file_name);
                  
                  $GLOBALS["db_fi"]->query("UPDATE esports_team SET custom_logo = '".$GLOBALS["db_fi"]->real_escape_string($file_name)."' WHERE id = '".$data["id"]."'");
                  addInstantMessage("Das Bild wurde erfolgreich hochgeladen.", "green");
               } else {
                  addInstantMessage("Konstante FLASHIGNITE_MEDIA_DIR ist nicht bzw. falsch gesetzt (evtl. auch keine Schreibrechte).", "red");
               }
            } else {
               addInstandMessage("Bitte g&uuml;tiges Bild hochladen.", "red");
            }
         }
      } else {
         addInstantMessage("Ung&uuml;tiger Linik.", "red");
      }
      header("Location: index.php?parser=esports&teams&edit_team=".trim($_POST["id"]));
   } else {
      $data = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_team WHERE id = '".$GLOBALS["db_fi"]->real_escape_string($_GET["edit_team"])."'"));
      if(isset($data["id"]) && $data["id"] > 0){
         $template = new template;
         $template->load("team_edit");
         
         foreach($data as $column => $value){
            $template->assign(strtoupper($column), $value);
         }
         
         $template->assign("SITE_TITLE", "Team bearbeiten");
         $tmpl = $template->display(true);
         $tmpl = $template->operators();
         echo $tmpl;
      } else {
         error_404();
      }
   }
} else {
   $teams_list = "";
   $query = $GLOBALS["db_fi"]->query("SELECT * FROM esports_team ORDER BY name ASC");
   while($row = $GLOBALS["db_fi"]->fetch_object($query)){
      $template = new template;
      $template->load("teams_list");
      foreach((array)$row as $column => $value){
         $template->assign(strtoupper($column), $value);
      }
      $teams_list .= $template->display();
   }
   
   $template = new template;
   $template->load("teams");
   $template->assign("SITE_TITLE", "Teams");
   $template->assign("TEAMS_LIST", $teams_list);
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}