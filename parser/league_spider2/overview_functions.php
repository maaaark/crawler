<?php

function getRunningCount(){
	$count = 0;
	$handle = opendir("logs/league_spider/running");
	while($datei = readdir($handle)){
		if(strpos($datei, ".crawler") > 0){
			$count++;
		}
	}
	return $count;
}

function getRunningRegion($region = "euw"){
	$count  = 0;
	$handle = opendir("logs/league_spider/running");
	while($datei = readdir($handle)){
		if(trim(strtolower($datei)) == trim(strtolower($region))."_running.txt"){
			$count++;
		}
	}
	return $count;
}