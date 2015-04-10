<?php

if(isset($_POST["change_league_spider_settings"])){
   $array = array();
   
   if(isset($_POST["game_version"])){
      $array["game_version"] = $_POST["game_version"];
   }
   
   if(isset($_POST["summoner_limit"])){
      $array["summoner_limit"] = $_POST["summoner_limit"];
   }
   
   if(isset($_POST["summoner_parse_limit"])){
      $array["summoner_parse_limit"] = $_POST["summoner_parse_limit"];
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
   
   if(isset($_POST["manual_sid_mode"]) && $_POST["manual_sid_mode"] == "true"){
      $array["manual_sid_mode"] = "true";
   } else {
      $array["manual_sid_mode"] = false;
   }
   
   if(isset($_POST["manual_sid_mode_count"]) && intval($_POST["manual_sid_mode_count"]) > 1){
      $array["manual_sid_mode_count"] = intval($_POST["manual_sid_mode_count"]);
   }
   
   $write_config = @file_put_contents("logs/league_spider/settings.conf", json_encode($array));
   
   if(isset($write_config) && $write_config){
      addInstantMessage("Die Einstellungen wurden erfolgreich gespeichert.", "green");
   } else {
      addInstantMessage("Einstellungen konnten nicht gespeichert werden.", "red");
   }
   header("Location: index.php?parser=league_spider&settings");
} else {
   $template = new template;
   $template->load("settings");

   $template->assign("SETTINGS_GAME_VERSION", GAME_VERSION);
   $template->assign("SETTINGS_SUMMONER_LIMIT", SUMMONER_LIMIT);
   $template->assign("SETTINGS_SUMMONER_PARSE_LIMIT", SUMMONER_PARSE_LIMIT);
   $template->assign("SETTINGS_SUMMONER_UPDATE_WAITING", SUMMONER_UPDATE_WAITING);
   $template->assign("SETTINGS_CRONJOB_INTERVAL", CRONJOB_INTERVAL);
   $template->assign("SETTINGS_ALLOWED_LEAGUES", implode(",", $allowed_leagues));
   
   if(MANUAL_SID_MODE){
      $template->assign("SETTINGS_MANUAL_SID_MODE", "TRUE");
   } else {
      $template->assign("SETTINGS_MANUAL_SID_MODE", "FALSE");
   }
   $template->assign("SETTINGS_MANUAL_SID_MODE_COUNT", MANUAL_SID_MODE_COUNT);

   $template->assign("SITE_TITLE", "League Spider Einstellungen");
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}