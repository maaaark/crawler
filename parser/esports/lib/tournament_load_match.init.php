<?php
set_time_limit(0);
require_once dirname(__FILE__).'/class/player.class.php';
require_once dirname(__FILE__).'/class/team.class.php';
require_once dirname(__FILE__).'/class/game.class.php';
require_once dirname(__FILE__).'/class/match.class.php';
require_once dirname(__FILE__).'/class/standings.class.php';

$match = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_match WHERE id = '".$GLOBALS["db_fi"]->real_escape_string($_GET["loadmatch"])."'"));

if(isset($match["id"]) && $match["id"] > 0){
   $content = @file_get_contents("http://na.lolesports.com:80/api/match/".trim($match["match_id"]).".json");
   if(isset($content) && $content && trim($content) != ""){
      $json = json_decode($content, true);
      
      $matchObject = new Match($json, $tournament);
      $matchObject->save();
      addInstantMessage("Das Match wurde erfolgreich aktualisiert.", "green");
   } else {
      addInstantMessage("API-Error beim laden der Match-Daten", "red");
   }
} else {
   addInstantMessage("Ung&uuml;ltiger Update Link", "red");
}
header("Location: index.php?parser=esports&league=".trim($_GET["league"])."&tournament=".trim($_GET["tournament"]));