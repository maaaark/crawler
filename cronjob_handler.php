<?php

require_once 'system/init.php';

if(isset($_GET["internal_request"])){
	$input = date("H:i:s d.m.Y").": Cronjob-Handler (cronjob_handler.php) aufgerufen\n";
	$datei = fopen("logs/cronjob.log.txt","a+");
	rewind($datei);
	fwrite($datei, $input);
	fclose($datei);
} else {
	echo "error";
}

?>