<?php

$config_file = array();
if(file_exists("logs/league_spider/settings.conf")){
   $config_file = @json_decode(file_get_contents("logs/league_spider/settings.conf"), true);
}

if(isset($config_file["cronjob_interval"]) && $config_file["cronjob_interval"]){
   define("CRONJOB_INTERVAL", $config_file["cronjob_interval"]);
} else {
   define("CRONJOB_INTERVAL", 3);
}

if(isset($config_file["manual_sid_mode"]) && $config_file["manual_sid_mode"] == "true"){
   define("MANUAL_SID_MODE", true);
} else {
   define("MANUAL_SID_MODE", false);
}

if(isset($config_file["manual_sid_mode_count"]) && intval($config_file["manual_sid_mode_count"]) > 0){
   define("MANUAL_SID_MODE_COUNT", intval($config_file["manual_sid_mode_count"]));
} else {
   define("MANUAL_SID_MODE_COUNT", 10);
}

if(isset($config_file["game_version"]) && $config_file["game_version"]){
   define("GAME_VERSION", $config_file["game_version"]);
} else {
   define("GAME_VERSION", "5.6");		  		// LoL-Version nach der gesucht werden soll
}

if(isset($config_file["summoner_limit"]) && trim($config_file["summoner_limit"]) != ""){
   define("SUMMONER_LIMIT", intval($config_file["summoner_limit"]));
} else {
   define("SUMMONER_LIMIT", 1);				// 0 = kein Limit
}

if(isset($config_file["summoner_parse_limit"]) && trim($config_file["summoner_parse_limit"]) != ""){
   define("SUMMONER_PARSE_LIMIT", intval($config_file["summoner_parse_limit"]));
} else {
   define("SUMMONER_PARSE_LIMIT", 1); 	   // Limit wie viele Summoners aus der Featured-Games-List genommen werden => je höher je mehr Leistung und Traffic nötig (0 = unbegrenzt)
}


if(isset($config_file["summoner_update_waiting"]) && trim($config_file["summoner_update_waiting"]) != ""){
   define("SUMMONER_UPDATE_WAITING", intval($config_file["summoner_update_waiting"]));
} else {
   define("SUMMONER_UPDATE_WAITING", 180); 	// Minuten die ein Summoner nach dem letzten laden nicht geupdated wird
}

define("CHANGE_TO_SID_MODE", 15);			// Ab wie vielen Summonern ohne neues Spiel den Crawling-Modus wechseln?
define("USE_SID_MODE", false);				// SummonerID Mode benutzen?

if(isset($config_file["allowed_leagues"]) && trim($config_file["allowed_leagues"])){
   $allowed_leagues = explode(",", $config_file["allowed_leagues"]);
   for($i = 0; $i < count($allowed_leagues); $i++){
      $allowed_leagues[$i] = trim($allowed_leagues[$i]);
   }
} else {
   $allowed_leagues = array("gold", "platinum", "diamond", "master", "challenger");
}

$allowed_queues  = array(4, 6); 	  		// 4 = Ranked Solo; 6 = 5er Ranked


require_once dirname(__FILE__).'/check.func.php';
?>
