
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

if($SID_MODE && USE_SID_MODE){
	echo "<div>SID-MODE:</div>";
} else {
	$start = new Start();
	$start->run($allowed_leagues, $allowed_queues);
}

echo "Crawlen fertiggestellt</body></html>";