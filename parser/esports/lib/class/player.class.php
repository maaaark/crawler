<?php

class Player {
   private $id;
   private $team_id;
   
   public function __construct($player_id, $team_id = false){
      $this->id      = $player_id;
      
      if($team_id != false){
         $this->team_id = $team_id;
      }
   }
   
   public function save(){
      // kommt noch
      return true;
   }
   
   /*
    * Aktualisiert den Spieler anhand von Roster-Informationen aus eine Match -> kein weiterer API-Call
   */
   public function short_update($roster_player_data, $team_id = false){
      if(isset($roster_player_data["playerId"]) && $roster_player_data["playerId"] > 0){
         $data = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_player WHERE player_id = '".$GLOBALS["db_fi"]->real_escape_string($roster_player_data["playerId"])."'"));
         
         if(isset($data["id"]) && $data["id"] > 0){
            $sql      = "UPDATE esports_player SET ";
            $sql_type = "update";
         } else {
            $sql      = "INSERT INTO esports_player SET player_id = '".$GLOBALS["db_fi"]->real_escape_string($roster_player_data["playerId"])."', ";
            $sql_type = "insert";
         }
         
         $sql .= "name         = '".$GLOBALS["db_fi"]->real_escape_string($roster_player_data["name"])."'";
         $sql .= ", role       = '".$GLOBALS["db_fi"]->real_escape_string($roster_player_data["role"])."'";
         $sql .= ", is_starter = '".$GLOBALS["db_fi"]->real_escape_string($roster_player_data["isStarter"])."'";
         
         if($team_id != false){
            $sql .= ", team_id = '".$GLOBALS["db_fi"]->real_escape_string($team_id)."'";
         }
         
         if($sql_type == "update"){
            $sql .= " WHERE id = '".$data["id"]."'";
         }
         $GLOBALS["db_fi"]->query($sql);
         return true;
      }
      return false;
   }
}