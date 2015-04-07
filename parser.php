<?php
require_once 'system/init.php';

require_once 'urf_matchID.class.php';
$speed   = 10; // 0 am schnellsten
$region  = "euw";

if(isset($_GET["region"])){
	$region = $_GET["region"];
}

$date = date("H:i d.m.Y", time() - (10 * 60));
$parser = new URF_matchID_parser(strtotime($date), $region);
$result = $parser->parse();
echo '<div class="message '.$result["status"].'">'.$result["message"].'</div>';


$next 		 = $_SERVER["PHP_SELF"];
$stop_link 	 = $_SERVER['REQUEST_URI'];
if(strpos($stop_link, "?") > 0){
	$stop_link = $stop_link."&stop";
} else {
	$stop_link = $stop_link."?stop";
}

echo "<div style='margin-top:40px;'>";
if(isset($_GET["stop"])){
	echo "<a href='".str_replace("&stop", "", str_replace("?stop", "", $stop_link))."'>Starten</a>";
} else {
	echo "<a href='".$stop_link."'>Stoppen</a>";
	echo '<meta http-equiv="refresh" content="'.$speed.'; url='.$next.'">';
}
echo "</div>";

?>