<?php

if(isset($_POST["id"])){
   $data = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_league WHERE id = '".$GLOBALS["db_fi"]->real_escape_string($_POST["id"])."'"));
   
   if(isset($data["id"]) && $data["id"] > 0){
      // Altes Bild löschen?
      if(isset($_POST["delete_custom_league_image"]) && $_POST["delete_custom_league_image"] == "true"){
         if(isset($data["custom_league_image"]) && trim($data["custom_league_image"]) != ""){
            if(defined("FLASHIGNITE_MEDIA_DIR") && trim(FLASHIGNITE_MEDIA_DIR) != ""){
               if(file_exists(FLASHIGNITE_MEDIA_DIR.trim($data["custom_league_image"]))){
                  unlink(FLASHIGNITE_MEDIA_DIR.trim($data["custom_league_image"]));
                  $update = $GLOBALS["db_fi"]->query("UPDATE esports_league SET custom_league_image = '' WHERE id = '".$data["id"]."'");
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
               
               $file_name = "/uploads/esports/league_".$data["id"]."_".str_replace(" ", "_", strtolower($data["short_name"])).".".$ext;
               move_uploaded_file($_FILES["custom_image"]["tmp_name"], FLASHIGNITE_MEDIA_DIR.$file_name);
               
               $GLOBALS["db_fi"]->query("UPDATE esports_league SET custom_league_image = '".$GLOBALS["db_fi"]->real_escape_string($file_name)."' WHERE id = '".$data["id"]."'");
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
   header("Location: index.php?parser=esports&edit_league=".trim($_POST["id"]));
} else {
   $data = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_league WHERE id = '".$GLOBALS["db_fi"]->real_escape_string($_GET["edit_league"])."'"));
   if(isset($data["id"]) && $data["id"] > 0){
      $template = new template;
      $template->load("edit_league");
      
      foreach($data as $column => $value){
         $template->assign(strtoupper($column), $value);
      }
      
      $template->assign("SITE_TITLE", "Liga bearbeiten");
      $tmpl = $template->display(true);
      $tmpl = $template->operators();
      echo $tmpl;
   } else {
      error_404();
   }
}