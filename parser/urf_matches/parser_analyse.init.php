<?php
define("ANALYSE_MATCH_COUNT", 10);
define("GAME_VERSION", "URF2015");
require_once dirname(__FILE__).'/lib/match.class.php';

function loadMatchId(){
   $data = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT * FROM urf_matchIDs WHERE status = '0' LIMIT 1"));
   
   if(isset($data["id"]) && $data["id"] > 0){
      $match = new Match($data["matchID"]);
      $match->analyse();
      $update = $GLOBALS["db"]->query("UPDATE urf_matchIDs SET status = '1' WHERE id = '".$data["id"]."'");
      return array("status" => true, "matchID" => $data["matchID"]);
   }
   return array("status" => false, "matchID" => 0);
}

$out    = "";
$count  = 0;
$status = true;

for($i = 1; $i <= ANALYSE_MATCH_COUNT; $i++){
   $analyse = loadMatchId();
   
   if($analyse["status"] == false){
      $status = false;
      $out .= "<div style='color:red;'>Die MatchID konnte nicht analysiert werden.";
   } else {
      $count++;
      $out .= "<div>Die MatchID ".$analyse["matchID"]." wurde erfolgreich analysiert.";
   }
}

if(trim($out) != "" && $count >= ANALYSE_MATCH_COUNT){
   $out = '<div class="message green">Es wurden '.$count.' MatchIDs erfolgreich geladen</div>'.$out;
} elseif(trim($out) != ""){
   $out = '<div class="message orange">Es wurden '.$count.' MatchIDs erfolgreich geladen</div>'.$out;
} else {
   $out = '<div class="message red">Es scheint als sei etwas schief gegangen. Es wurden '.$count.' MatchIDs erfolgreich geladen</div>'.$out;
}

echo $out;