
<?php
require_once dirname(__FILE__).'/lib/config.php';
echo "<body>Aktuelle LoL-Version: ".GAME_VERSION."<hr/>";

require_once dirname(__FILE__).'/lib/crawler/match.class.php';
require_once dirname(__FILE__).'/lib/crawler/matchhistory.class.php';
require_once dirname(__FILE__).'/lib/crawler/summoner.class.php';
require_once dirname(__FILE__).'/lib/crawler/start.class.php';

$SID_MODE = false; // Crawlen per SummonerIDs in DB -> wenn hier auf False wird FeaturedGames API benutzt
if(file_exists("log/league_spider/change_mode.txt")){
	$content = file_get_contents("log/league_spider/change_mode.txt");
	if(trim($content) == "TRUE"){
		$SID_MODE = true;
	}
	unlink("log/league_spider/change_mode.txt");
}

if($SID_MODE && USE_SID_MODE || MANUAL_SID_MODE){
	echo "<div>SID-MODE:</div>";
	for($i = 0; $i < MANUAL_SID_MODE_COUNT; $i++){
      $date = date("Y-m-d H:i:s", time() - (60*SUMMONER_UPDATE_WAITING));
      $data = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT * FROM lol_league_parser_summoner WHERE last_update < '".$GLOBALS["db"]->real_escape_string($date)."'"));
      
      if(isset($data["id"]) && $data["id"] > 0){
         $summoner = new Summoner($data["id"]);
         $summoner->analyseLeague();
      }
	}
	
} else {
	$start = new Start();
	$start->run($allowed_leagues, $allowed_queues);
}

echo "Crawlen fertiggestellt</body></html>";