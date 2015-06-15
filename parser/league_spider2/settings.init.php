<?php

if(isset($_POST["change_league_spider_settings"])){
   $array = array();
   
   if(isset($_POST["game_version"])){
      $array["game_version"] = $_POST["game_version"];
   }
   
   if(isset($_POST["summoner_limit"])){
      $array["summoner_limit"] = $_POST["summoner_limit"];
   }
   
   if(isset($_POST["summoner_update_waiting"])){
      $array["summoner_update_waiting"] = $_POST["summoner_update_waiting"];
   }
   
   if(isset($_POST["allowed_leagues"])){
      $array["allowed_leagues"] = $_POST["allowed_leagues"];
   }
   
   if(isset($_POST["cronjob_interval"])){
      $array["cronjob_interval"] = $_POST["cronjob_interval"];
   }
   
   if(isset($_POST["league_spider_mode"])){
      $array["league_spider_mode"] = $_POST["league_spider_mode"];
   }
   
   if(isset($_POST["queue_matches_limit"])){
      $array["queue_matches_limit"] = $_POST["queue_matches_limit"];
   }
   
   if(isset($_POST["queue_summoner_limit"])){
      $array["queue_summoner_limit"] = $_POST["queue_summoner_limit"];
   }

   if(isset($_POST["save_new_summoner"]) && $_POST["save_new_summoner"] == "true"){
      $array["save_new_summoner"] = true;
   } else {
      $array["save_new_summoner"] = false;
   }
   
   $write_config = @file_put_contents("logs/league_spider/settings.conf", json_encode($array));
   
   if(isset($write_config) && $write_config){
      addInstantMessage("Die Einstellungen wurden erfolgreich gespeichert.", "green");
   } else {
      addInstantMessage("Einstellungen konnten nicht gespeichert werden.", "red");
   }
   header("Location: index.php?parser=league_spider2&settings");
} else {
   $template = new template;
   $template->load("settings");

   $template->assign("SETTINGS_GAME_VERSION", GAME_VERSION);
   $template->assign("SETTINGS_SUMMONER_LIMIT", SUMMONER_LIMIT);
   $template->assign("SETTINGS_SUMMONER_UPDATE_WAITING", SUMMONER_UPDATE_WAITING);
   $template->assign("SETTINGS_CRONJOB_INTERVAL", CRONJOB_INTERVAL);
   $template->assign("SETTINGS_ALLOWED_LEAGUES", implode(",", $allowed_leagues));
   $template->assign("SETTINGS_LEAGUE_SPIDER_MODE", LEAGUE_SPIDER_MODE);
   
   $template->assign("SETTINGS_QUEUE_MATCHES_LIMIT", QUEUE_MATCHES_LIMIT);
   $template->assign("SETTINGS_QUEUE_SUMMONER_LIMIT", QUEUE_SUMMONER_LIMIT);

   $template->assign("SETTINGS_SAVE_NEW_SUMMONER", SAVE_NEW_SUMMONER);

   $template->assign("SITE_TITLE", "League Spider Einstellungen");
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}