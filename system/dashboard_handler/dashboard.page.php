<?php

if(isset($_POST["dashboard_message"])){
   $datei = fopen("logs/dashboard_message.txt","w+");
   rewind($datei);
   fwrite($datei, trim($_POST["dashboard_message"]));
   fclose($datei);
   
   addInstantMessage("Die Dashboard Nachricht wurde erfolgreich aktualisiert.", "green");
   header("Location: index.php");
} else {
   $need_version_update = true;
   $lol_version         = "";
   if(file_exists("logs/lol_version.txt")){
      $lol_version = trim(file_get_contents("logs/lol_version.txt"));
      if(file_exists("logs/lol_version.log.txt")){
         $date1   = date('Y-m-d H:i:s');
         $date2 = file_get_contents("logs/lol_version.log.txt");
         $diff    = abs(strtotime($date2) - strtotime($date1));
         $mins    = floor($diff / 60);
         if($mins < 180 && trim($lol_version) != ""){
            $need_version_update = false;
         }
      }
   }
   
   if($need_version_update){
      $lol_version_api = @file_get_contents("https://global.api.pvp.net/api/lol/static-data/euw/v1.2/versions?api_key=".RIOT_KEY);
      if($lol_version_api){
         $json = json_decode($lol_version_api, true);
         
         if($json && is_array($json) && isset($json[0])){
            $log_put         = @file_put_contents("logs/lol_version.log.txt", date('Y-m-d H:i:s'));
            $lol_version_put = @file_put_contents("logs/lol_version.txt", $json[0]);
            $lol_version     = $json[0];
            
            if($log_put && $lol_version_put){
               addInstantMessage("Die LoL-Version wurde aktualisiert.", "green");
            } else {
               addInstantMessage("Die LoL-Versionsaktualisierung konnte aufgrund von Schreibrechten nicht gespeichert werden.", "red");
            }
         } else {
            addInstantMessage("Es wurde probiert die LoL-Version zu aktualisieren ... Ging aber schief.", "orange");
         }
      } else {
         addInstantMessage("Die LoL-Version konnte nicht aktualisiert werden.", "red");
      }
      header("Location: index.php");
      die();
   }
   
   $dashboard_message = "Keine Nachricht angegeben";
   if(file_exists("logs/dashboard_message.txt")){
      $dashboard_message = @file_get_contents("logs/dashboard_message.txt");
   }
   
   $template = new Template;
   $template->load("index");
   $template->assign("SITE_TITLE", "Dashboard");
   $template->assign("DASHBOARD_MESSAGE", $dashboard_message);
   $template->assign("IS_INDEX", true);
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}