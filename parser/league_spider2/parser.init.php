
<?php
require_once dirname(__FILE__).'/lib/config.php';

if(isset($argv) && isset($argv[1])){ // Konsolen Aufruf
	echo "Aktuelle LoL-Version: ".GAME_VERSION.PHP_EOL;
} else { // Browser aufruf
	echo "<body>Aktuelle LoL-Version: ".GAME_VERSION."<hr/>";
}


$load_region = "euw";
if(isset($_GET["region_value"])){
	if(trim(strtolower($_GET["region_value"])) == "na"){
		$load_region = "na";
	}
	elseif(trim(strtolower($_GET["region_value"])) == "eune"){
		$load_region = "eune";
	}
}

if(isset($argv[2])){
	if(trim(strtolower($argv[2])) == "na"){
		$load_region = "na";
	}
	elseif(trim(strtolower($argv[2])) == "eune"){
		$load_region = "eune";
	}
}

if(defined("LEAGUE_SPIDER_MODE") && LEAGUE_SPIDER_MODE == "queue"){
   include dirname(__FILE__).'/modes/queue_mode.start.php';
} else {
   include dirname(__FILE__).'/modes/normal_mode.start.php';
}