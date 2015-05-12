<?php

class Standings {
   private $tournament;
   
   public function __construct($tournament_id){
      $this->tournament = $tournament_id;
   }
   
   public function save(){
      $content = @file_get_contents("http://na.lolesports.com:80/api/standings.json?tournamentId=".trim($this->tournament));
      if($content){
         $json = json_decode($content, true);
         if(isset($json[0]) && is_array($json) && is_array($json[0])){
            $delete = $GLOBALS["db_fi"]->query("DELETE FROM esports_standings WHERE tournament_id = '".$GLOBALS["db_fi"]->real_escape_string($this->tournament)."'");
            foreach($json as $element){
               $sql = "INSERT INTO esports_standings SET tournament_id = '".$GLOBALS["db_fi"]->real_escape_string($this->tournament)."',
                                                         wins          = '".$GLOBALS["db_fi"]->real_escape_string($element["wins"])."',
                                                         losses        = '".$GLOBALS["db_fi"]->real_escape_string($element["losses"])."',
                                                         rank          = '".$GLOBALS["db_fi"]->real_escape_string($element["teamRank"])."',
                                                         team_id       = '".$GLOBALS["db_fi"]->real_escape_string($element["teamId"])."'";
               $GLOBALS["db_fi"]->query($sql);
            }
            addInstantMessage("Die Tabelle des Turniers wurde erfolgreich aktualisiert", "green");
         } else {
            addInstantMessage("Die Tabelle des Turniers konnte aufgrund von nicht verwertbaren Daten nicht aktualisiert werden.", "red");
         }
      } else {
         addInstantMessage("Tabelle konnte wegen eines API Errors nicht aktualisiert werden", "red");
      }
   }
}