<?php

class Match {
   private $data;
   private $tournament;
   
   public function __construct($match_data, $tournament_data){
      $this->data       = $match_data;
      $this->tournament = $tournament_data;
   }
   
   public function save(){
      $match = $this->data;
      $check = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_match WHERE match_id = '".$GLOBALS["db_fi"]->real_escape_string($match["matchId"])."'"));
      if(isset($check["id"]) && $check["id"] > 0){
         $sql      = "UPDATE esports_match SET ";
         $sql_type = "update";
      } else {
         $sql      = "INSERT INTO esports_match SET match_id = '".$GLOBALS["db_fi"]->real_escape_string($match["matchId"])."', ";
         $sql_type = "insert";
      }
      $sql .= "tournament_id = '".$GLOBALS["db_fi"]->real_escape_string($this->tournament["tournament_id"])."'";
      
      $winner = 0;
      if(isset($match["winnerId"]) && $match["winnerId"] > 0){
         $winner = $match["winnerId"];
      }
      $sql .= ", winner = '".$GLOBALS["db_fi"]->real_escape_string($winner)."'";
      
      $date = "0000-00-00 00:00:00";
      if(isset($match["dateTime"]) && trim($match["dateTime"]) != ""){
         $date = date("Y-m-d H:i:s", strtotime($match["dateTime"]));
      }
      $sql .= ", date = '".$GLOBALS["db_fi"]->real_escape_string($date)."'";
      
      $riot_url = "";
      if(isset($match["url"]) && trim($match["url"]) != ""){
         $riot_url = $match["url"];
      }
      $sql .= ", riot_url = '".$GLOBALS["db_fi"]->real_escape_string($riot_url)."'";
      
      $max_games = 1;
      if(isset($match["maxGames"]) && $match["maxGames"] > 1){
         $max_games = $match["maxGames"];
      }
      $sql .= ", max_games = '".$GLOBALS["db_fi"]->real_escape_string($max_games)."'";
      
      $is_finished = 0;
      if(isset($match["isFinished"]) && $match["isFinished"] > 0){
         $is_finished = $match["isFinished"];
      }
      $sql .= ", is_finished = '".$GLOBALS["db_fi"]->real_escape_string($is_finished)."'";
      
      $tournament_round = 1;
      if(isset($match["tournament"]["round"]) && $match["tournament"]["round"] > 0){
         $tournament_round = $match["tournament"]["round"];
      }
      $sql .= ", tournament_round = '".$GLOBALS["db_fi"]->real_escape_string($tournament_round)."'";
      
      
      // Teams
      if(isset($match["contestants"]) && isset($match["contestants"]["red"]) && isset($match["contestants"]["blue"])){
         if(isset($match["contestants"]["blue"]["id"])){
            $sql .= ", team1_id = '".$GLOBALS["db_fi"]->real_escape_string($match["contestants"]["blue"]["id"])."'";
            $team1 = new Team($match["contestants"]["blue"]["id"]);
            $team1->save();
            $team1 = null;
         }
         
         if(isset($match["contestants"]["red"]["id"])){
            $sql .= ", team2_id = '".$GLOBALS["db_fi"]->real_escape_string($match["contestants"]["red"]["id"])."'";
            $team2 = new Team($match["contestants"]["red"]["id"]);
            $team2->save();
            $team2 = null;
         }
      }
      
      $polldaddy_id = "";
      if(isset($match["polldaddyId"]) && trim($match["polldaddyId"]) != ""){
         $polldaddy_id = $match["polldaddyId"];
      }
      $sql .= ", polldaddy_id = '".$GLOBALS["db_fi"]->real_escape_string($polldaddy_id)."'";
      
      $name = "";
      if(isset($match["name"]) && trim($match["name"]) != ""){
         $name = $match["name"];
      }
      $sql .= ", name = '".$GLOBALS["db_fi"]->real_escape_string($name)."'";
      
      // Games
      $games = array();
      if(isset($match["games"]) && is_array($match["games"])){
         foreach($match["games"] as $game){
            if(isset($game["id"])){
               $games[] = $game["id"];
               $gameObject = new Game($game["id"], $match);
               $gameObject->save();
               $gameObject = null;
            }
         }
      }
      $sql .= ", games = '".$GLOBALS["db_fi"]->real_escape_string(json_encode($games))."'";

      if($sql_type == "update"){
         $sql .= " WHERE id = '".$GLOBALS["db_fi"]->real_escape_string($check["id"])."'";
      }
      
      //echo "<pre>", print_r($match), "</pre>";
      //echo $sql."<hr/>";
      $GLOBALS["db_fi"]->query($sql);
   }
}