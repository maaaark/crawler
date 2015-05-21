
<?php
require_once dirname(__FILE__).'/lib/config.php';
echo "<body>Aktuelle LoL-Version: ".GAME_VERSION."<hr/>";

$load_region = "euw";
if(isset($_GET["region_value"])){
	if(trim(strtolower($_GET["region_value"])) == "na"){
		$load_region = "na";
	}
}


if(file_exists("logs/league_spider/running/".$load_region."_running.txt")){
	echo "Es laueft bereits ein Crawler fuer ".$load_region;
} else {
	// Region-load Markierung setzen
	$datei = fopen("logs/league_spider/running/".$load_region."_running.txt","w+");
	rewind($datei);
	fwrite($datei, date('Y_m_d__h.i.s'));
	fclose($datei);

	$league_spider = new LeagueSpider($load_region);
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
		$datei = fopen("logs/league_spider/error_logs/error.".date('Y_m_d__h_i_s').".log.txt","w+");
		rewind($datei);
		fwrite($datei, json_encode($log));
		fclose($datei);
	}

	// Region-load Markierung entfernen
	if(file_exists("logs/league_spider/running/".$load_region."_running.txt")){
		unlink("logs/league_spider/running/".$load_region."_running.txt");
	}
}