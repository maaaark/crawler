<?php
set_time_limit(0);
require_once dirname(__FILE__).'/class/player.class.php';
require_once dirname(__FILE__).'/class/team.class.php';
require_once dirname(__FILE__).'/class/game.class.php';
require_once dirname(__FILE__).'/class/match.class.php';

$content = @file_get_contents("http://na.lolesports.com:80/api/schedule.json?tournamentId=".trim($tournament["tournament_id"])."&includeFinished=true&includeFuture=true");
if($content){
   $json  = json_decode($content, true);
   $count = 0;
   if(isset($json) && is_array($json)){
      foreach($json as $match_column => $match){   
         if(isset($match["matchId"]) && $match["matchId"] > 0){
            $matchObject = new Match($match, $tournament);
            $matchObject->save();
            $matchObject = null;
            $count++;
         } else {
            addInstantMessage("Das Match ".$match_column." konnte nicht verarbeitet werden.", "orange");
         }
      }

      if(isset($_SESSION["esports_parser_teams"])){ // Temporäre Team Daten zurücksetzen
         $_SESSION["esports_parser_teams"] = "";
      }
   } else {
      addInstantMessage("Die Riot API hat eine nicht brauchbare Antwort zur&uuml;ckgegeben.", "red");
   }
   
   if($count == 0){
      addInstantMessage("Es wurden keine Spiele aktualisiert.", "red");
   } else {
      addInstantMessage("Der Spielplan wurde erfolgreich aktualisiert.", "green");
   }
} else {
   addInstantMessage("Die Riot API hat keine Antwort zur&uuml;ckgegeben.", "red");
}
header("Location: index.php?parser=esports&league=".trim($data["id"])."&tournament=".trim($tournament["id"]));