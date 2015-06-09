<?php

require_once dirname(__FILE__).'/system/init.php';

$deactivated = false;
if(isset($deactivated) && $deactivated){
	die();
}

// $argv für konsolen anwendungen: beispiel start: php cronjob_handler.php internal_request euw
if(isset($_GET["internal_request"]) || isset($argv) && isset($argv[1]) && trim(strtolower($argv[1])) == "internal_request"){
	set_time_limit(60 * 9);

	if(isset($_GET) && isset($_GET["internal_request"])){
		$input = date("H:i:s d.m.Y").": Cronjob-Handler (cronjob_handler.php) aufgerufen. GET: ".trim(substr(str_replace("Array(", "", str_replace("\n", "", print_r($_GET, true))), 0, -1))."\n";
	} else {
		$input = date("H:i:s d.m.Y").": Cronjob-Handler (cronjob_handler.php) aufgerufen. ARGV = ".trim(substr(str_replace("Array(", "", str_replace("\n", "", print_r($argv, true))), 0, -1))."\n";
	}
	$datei = fopen(ROOT_DIR."/logs/cronjob.log.txt","a+");
	rewind($datei);
	fwrite($datei, $input);
	fclose($datei);

	$running_id = time()."_".randomString(5).".crawler";
	$input = date('Y-m-d H:i:s')."; PID: ".getmypid();
	$datei = fopen(ROOT_DIR."/logs/league_spider/running/".$running_id,"w+");
	rewind($datei);
	fwrite($datei, $input);
	fclose($datei);

	// Neue League-Spider
	require_once ROOT_DIR.'/parser/league_spider2/parser.init.php';

	if(file_exists(ROOT_DIR."/logs/league_spider/running/".$running_id)){
		unlink(ROOT_DIR."/logs/league_spider/running/".$running_id);

		if(isset($argv) && isset($argv[1])){
			echo PHP_EOL."Process-Data: deleted #".$running_id;
		} else {
			echo "<br/>Process-Data: deleted #".$running_id;
		}
	}

} elseif(isset($_GET["internal_request2"])){
	/*// Alter League-Spider Crawler
   require_once 'parser/league_spider/lib/config.php';
   
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
		$date2 = date('Y-m-d H:i:s', time() - (5 * 60));
	}
	$diff    = abs(strtotime($date2) - strtotime($date1));
	$mins    = floor($diff / 60);
	
	if($mins < CRONJOB_INTERVAL){
		$league_spider = false;
	}

	if($league_spider){
		$input = date('Y-m-d H:i:s');
		$datei = fopen("logs/league_spider/last_update.log.txt","w+");
		rewind($datei);
		fwrite($datei, $input);
		fclose($datei);
		require_once 'parser/league_spider/parser.init.php';
	}*/
} else {
	echo "error";
}

?>
