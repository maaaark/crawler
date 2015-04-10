<?php

require_once 'system/init.php';

if(isset($_GET["internal_request"])){
   set_time_limit(0);
   
	$input = date("H:i:s d.m.Y").": Cronjob-Handler (cronjob_handler.php) aufgerufen\n";
	$datei = fopen("logs/cronjob.log.txt","a+");
	rewind($datei);
	fwrite($datei, $input);
	fclose($datei);


	$league_spider = true;
	$need_api_request = true;
	$date1   = date('Y-m-d H:i:s');
	if(file_exists("logs/league_spider/last_update.log.txt")){
		$date2 = file_get_contents("logs/league_spider/last_update.log.txt");
	} else {
		$date2 = date('Y-m-d H:i:s', time() - (5 * 60 * 3));
	}
	$diff    = abs(strtotime($date2) - strtotime($date1));
	$mins    = floor($diff / 60);
	if($mins < 3){
		$league_spider = false;
	}

	if($league_spider){
		$input = date('Y-m-d H:i:s');
		$datei = fopen("logs/league_spider/last_update.log.txt","w+");
		rewind($datei);
		fwrite($datei, $input);
		fclose($datei);
		require_once 'parser/league_spider/parser.init.php';
	}
} else {
	echo "error";
}

?>