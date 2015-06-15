<?php

$config_file = array();
if(file_exists(ROOT_DIR."/logs/league_spider/settings.conf")){
   $config_file = @json_decode(file_get_contents(ROOT_DIR."/logs/league_spider/settings.conf"), true);
}

if(isset($config_file["cronjob_interval"]) && $config_file["cronjob_interval"]){
   define("CRONJOB_INTERVAL", $config_file["cronjob_interval"]);
} else {
   define("CRONJOB_INTERVAL", 3);
}

if(isset($config_file["game_version"]) && $config_file["game_version"]){
   define("GAME_VERSION", $config_file["game_version"]);
} else {
   define("GAME_VERSION", "5.9");		  		// LoL-Version nach der gesucht werden soll
}

if(isset($config_file["summoner_limit"]) && trim($config_file["summoner_limit"]) != ""){
   define("SUMMONER_LIMIT", intval($config_file["summoner_limit"]));
} else {
   define("SUMMONER_LIMIT", 100);
}

if(isset($config_file["summoner_update_waiting"]) && trim($config_file["summoner_update_waiting"]) != ""){
   define("SUMMONER_UPDATE_WAITING", intval($config_file["summoner_update_waiting"]));
} else {
   define("SUMMONER_UPDATE_WAITING", 180); 	// Minuten die ein Summoner nach dem letzten laden nicht geupdated wird
}

if(isset($config_file["queue_matches_limit"]) && trim($config_file["queue_matches_limit"]) != ""){
   define("QUEUE_MATCHES_LIMIT", intval($config_file["queue_matches_limit"]));
} else {
   define("QUEUE_MATCHES_LIMIT", 10); 	// Anzahl der Matches die der Queue Mode auf einmal aktualisiert
}

if(isset($config_file["queue_summoner_limit"]) && trim($config_file["queue_summoner_limit"]) != ""){
   define("QUEUE_SUMMONER_LIMIT", intval($config_file["queue_summoner_limit"]));
} else {
   define("QUEUE_SUMMONER_LIMIT", 10); 	// Anzahl der Summoner-Histories die aktualisiert werden wenn die Queue leer ist
}

if(Isset($config_file["save_new_summoner"]) && $config_file["save_new_summoner"] == false){
   define("SAVE_NEW_SUMMONER", false);
} else {
   define("SAVE_NEW_SUMMONER", true);
}

if(isset($config_file["allowed_leagues"]) && trim($config_file["allowed_leagues"])){
   $allowed_leagues = explode(",", $config_file["allowed_leagues"]);
   for($i = 0; $i < count($allowed_leagues); $i++){
      $allowed_leagues[$i] = trim($allowed_leagues[$i]);
   }
} else {
   $allowed_leagues = array("gold", "platinum", "diamond", "master", "challenger");
}

$allowed_queues  = array(4, 6); 	  		// 4 = Ranked Solo; 6 = 5er Ranked

// League-Spider Modes
define("LEAGUE_SPIDER_NORMAL_MODE", 1);
define("LEAGUE_SPIDER_QUEUE_MODE", 2);

if(isset($config_file["league_spider_mode"]) && trim($config_file["league_spider_mode"]) != ""){
   define("LEAGUE_SPIDER_MODE", trim($config_file["league_spider_mode"]));
} else {
   define("LEAGUE_SPIDER_MODE", "normal");
}

function check_array($array, $value){
	for($i = 0; $i<count($array); $i++){
		if($array[$i] == $value){
			return true;
		}
	}
	return false;
}

require_once dirname(__FILE__).'/match.class.php';
require_once dirname(__FILE__).'/league_spider_log.class.php';
require_once dirname(__FILE__).'/league_spider.class.php';