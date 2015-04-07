<?php
$region  = "euw";

if(isset($_GET["region"])){
	$region = $_GET["region"];
}

$time = time();
if(isset($_GET["timestamp"]) && trim($_GET["timestamp"]) != ""){
	$time = strtotime($_GET["timestamp"]);
}

$minutes = date("i", $time);
$diff    = $minutes % 5;
if($diff > 0){
	$minutes = $minutes - 5 - $diff;
	$time = $time - ($diff * 60) - (5 * 60);
}

$date = date("H:i d.m.Y", $time);

$parser = new URF_matchID_parser(strtotime($date), $region);
$result = $parser->parse();
$array  = array();
$array["result"] = '<div class="message '.$result["status"].'"><span style="float:right;">'.$date.'</span>'.$result["message"].'</div>';
$array["next"]   = date("H:i:s d.m.Y", strtotime($date) - (5 * 60));
echo json_encode($array);
?>