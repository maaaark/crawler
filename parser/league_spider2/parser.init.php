
<?php
require_once dirname(__FILE__).'/lib/config.php';
echo "<body>Aktuelle LoL-Version: ".GAME_VERSION."<hr/>";

$league_spider = new LeagueSpider();
$league_spider->run(LEAGUE_SPIDER_NORMAL_MODE);

$log = $league_spider->getLog();
echo "<pre>";print_r($log);echo "</pre>";

$input = date('Y-m-d H:i:s');
$datei = fopen("logs/league_spider/last_update.log.txt","w+");
rewind($datei);
fwrite($datei, $input);
fclose($datei);

$datei = fopen("logs/league_spider/success.log.txt","a+");
rewind($datei);
fwrite($datei, $log["final_message"]."\n");
fclose($datei);

if($log["error_count"] > 0){
	$date = date('Y_m_d__h.i.s');
	$datei = fopen("logs/league_spider/error_logs/error.".$date.".log.txt","w+");
	rewind($datei);
	fwrite($datei, json_encode($log));
	fclose($datei);
}