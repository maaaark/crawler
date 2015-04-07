<?php
define("GAME_VERSION", "5.6");		  		// LoL-Version nach der gesucht werden soll
define("SUMMONER_LIMIT", 1);				// 0 = kein Limit
define("SUMMONER_PARSE_LIMIT", 1); 			// Limit wie viele Summoners aus der Featured-Games-List genommen werden => je höher je mehr Leistung und Traffic nötig (0 = unbegrenzt)
define("SUMMONER_UPDATE_WAITING", 180); 	// Minuten die ein Summoner nach dem letzten laden nicht geupdated wird
define("CHANGE_TO_SID_MODE", 15);			// Ab wie vielen Summonern ohne neues Spiel den Crawling-Modus wechseln?
define("USE_SID_MODE", false);				// SummonerID Mode benutzen?

$allowed_leagues = array("gold", "platinum", "diamond", "master", "challenger");
$allowed_queues  = array(4, 6); 	  		// 4 = Ranked Solo; 6 = 5er Ranked


require_once dirname(__FILE__).'/check.func.php';
?>
