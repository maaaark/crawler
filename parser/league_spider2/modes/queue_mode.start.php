<?php

// Checken ob bereits anderer Crawler f�r diese Region l�uft
$crawl_enabled = true;
if(file_exists(ROOT_DIR."/logs/league_spider/running/queue_mode_running.txt")){
	$crawl_enabled   = false;
	$content_running = file_get_contents(ROOT_DIR."/logs/league_spider/running/queue_mode_running.txt");
	$date2 			 = substr($content_running, 0, strpos($content_running, ":"));
	$date2			 = str_replace("_", "-", substr($date2, 0, strpos($date2, "__")))." ".str_replace(".", ":", substr($date2, strpos(trim($date2), "__")+2));
	$date1       	 = date('Y-m-d H:i:s');
	$diff       	 = abs(strtotime($date2) - strtotime($date1));
	$mins        	 = floor($diff / 60);
	
	if($mins > 10){ // Wenn diese Region schon seit 5 Minuten geblockt ist: freischalten
		$crawl_enabled = true;
	}
}

if($crawl_enabled == false){
	echo "Es laueft bereits ein Queue-Mode Crawler";
} else {
   // Region-load Markierung setzen
	$datei = fopen(ROOT_DIR."/logs/league_spider/running/queue_mode_running.txt","w+");
	rewind($datei);
	fwrite($datei, date('Y_m_d__H.i.s').": Running-ID: ".$running_id);
	fclose($datei);
	
	$league_spider = new LeagueSpider($load_region);
	$league_spider->run(LEAGUE_SPIDER_QUEUE_MODE);

	$log = $league_spider->getLog();
	
	if(isset($argv) && isset($argv[1])){
		print_r($log);
		echo PHP_EOL;
	} else {
		echo "<pre>";print_r($log);echo "</pre>";
	}
	
	$input = date('Y-m-d H:i:s');
	$datei = fopen(ROOT_DIR."/logs/league_spider/last_update.log.txt","w+");
	rewind($datei);
	fwrite($datei, $input);
	fclose($datei);

	$datei = fopen(ROOT_DIR."/logs/league_spider/success.log.txt","a+");
	rewind($datei);
	fwrite($datei, $log["final_message"]."\n");
	fclose($datei);
	
	if($log["error_count"] > 0){
		$datei = fopen(ROOT_DIR."/logs/league_spider/error_logs/error.".date('Y_m_d__h_i_s').".log.txt","w+");
		rewind($datei);
		fwrite($datei, json_encode($log));
		fclose($datei);
	}

	// Region-load Markierung entfernen
	if(file_exists(ROOT_DIR."/logs/league_spider/running/queue_mode_running.txt")){
		unlink(ROOT_DIR."/logs/league_spider/running/queue_mode_running.txt");
	}
}