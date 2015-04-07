<?php

$region  = "euw";
if(isset($_GET["region"])){
	$region = $_GET["region"];
}

$date = date("H:i d.m.Y", time() - (10 * 60));
$parser = new URF_matchID_parser(strtotime($date), $region);
$result = $parser->parse();
echo '<div class="message '.$result["status"].'">'.$result["message"].'</div>';

?>