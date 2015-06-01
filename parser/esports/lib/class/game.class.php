<?php

class Game {
   private $game_id;
   private $match_data;
   
   public function __construct($game_id, $match){
      $this->match_data = $match;
      $this->game_id    = $game_id;
   }
   
   public function save(){
      $content = @file_get_contents("http://na.lolesports.com:80/api/game/".trim($this->game_id).".json");
      if($content){
         $json = json_decode($content, true);
         
         $check = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_game WHERE game_id = '".$GLOBALS["db_fi"]->real_escape_string($this->game_id)."'"));
         if(isset($check["id"]) && $check["id"] > 0){
            $sql      = "UPDATE esports_game SET ";
            $sql_type = "update";
         } else {
            $sql      = "INSERT INTO esports_game SET game_id = '".$GLOBALS["db_fi"]->real_escape_string($this->game_id)."', ";
            $sql_type = "insert";
         }
         
         $winner = 0;
         if(isset($json["winnerId"]) && $json["winnerId"] > 0){
            $winner = $json["winnerId"];
         }
         $sql .= "winner = '".$GLOBALS["db_fi"]->real_escape_string($winner)."'";
         
         $game_number = 0;
         if(isset($json["gameNumber"]) && $json["gameNumber"] > 0){
            $game_number = $json["gameNumber"];
         }
         $sql .= ", game_number = '".$GLOBALS["db_fi"]->real_escape_string($game_number)."'";
         
         $game_length = 0;
         if(isset($json["gameLength"]) && $json["gameLength"] > 0){
            $game_length = $json["gameLength"];
         }
         $sql .= ", game_length = '".$GLOBALS["db_fi"]->real_escape_string($game_length)."'";
         
         $match_id = 0;
         if(isset($json["matchId"]) && $json["matchId"] > 0){
            $match_id = $json["matchId"];
         }
         $sql .= ", match_id = '".$GLOBALS["db_fi"]->real_escape_string($match_id)."'";
         
         $max_games = 1;
         if(isset($json["maxGames"]) && $json["maxGames"] > 0){
            $max_games = $json["maxGames"];
         }
         $sql .= ", max_games = '".$GLOBALS["db_fi"]->real_escape_string($max_games)."'";
         
         $tournament_id = 0;
         if(isset($json["tournament"]) && isset($json["tournament"]["id"]) && $json["tournament"]["id"] > 0){
            $tournament_id = $json["tournament"]["id"];
         }
         $sql .= ", tournament_id = '".$GLOBALS["db_fi"]->real_escape_string($tournament_id)."'";
         
         $tournament_round = 1;
         if(isset($json["tournament"]) && isset($json["tournament"]["round"]) && $json["tournament"]["round"] > 0){
            $tournament_round = $json["tournament"]["round"];
         }
         $sql .= ", tournament_round = '".$GLOBALS["db_fi"]->real_escape_string($tournament_round)."'";
         
         $youtube_video = "";
         if(isset($json["vods"]) && is_array($json["vods"])){
            foreach($json["vods"] as $video){
               if(isset($video["URL"]) && isset($video["type"]) && trim($video["URL"]) != "" && strtolower(trim($video["type"])) == "youtube"){
                  $youtube_video = $video["URL"];
               }
            }
         }
         $sql .= ", youtube_video = '".$GLOBALS["db_fi"]->real_escape_string($youtube_video)."'";
         
         $blueteam_id = 1;
         if(isset($json["contestants"]) && isset($json["contestants"]["blue"]) && isset($json["contestants"]["blue"]["id"]) && $json["contestants"]["blue"]["id"] > 0){
            $blueteam_id = $json["contestants"]["blue"]["id"];
         }
         $sql .= ", blueteam_id = '".$GLOBALS["db_fi"]->real_escape_string($blueteam_id)."'";
         
         $redteam_id = 1;
         if(isset($json["contestants"]) && isset($json["contestants"]["red"]) && isset($json["contestants"]["red"]["id"]) && $json["contestants"]["red"]["id"] > 0){
            $redteam_id = $json["contestants"]["red"]["id"];
         }
         $sql .= ", redteam_id = '".$GLOBALS["db_fi"]->real_escape_string($redteam_id)."'";
         
         $players = array();
         if(isset($json["players"]) && is_array($json["players"])){
            $players = $json["players"];
            $this->updatePlayers($players);
         }
         $sql .= ", players = '".$GLOBALS["db_fi"]->real_escape_string(json_encode($players))."'";
         
         if($sql_type == "update"){
            $sql .= " WHERE id = '".$GLOBALS["db_fi"]->real_escape_string($check["id"])."'";
         }
         //echo $sql;
         //echo "<pre>", print_r($json), "</pre>";
         $GLOBALS["db_fi"]->query($sql);
      }
   }

   private function updatePlayers($players){
      foreach($players as $player){
         if(isset($player["id"]) && isset($player["photoURL"])){
            $sql    = "UPDATE esports_player SET pic = '".$GLOBALS["db_fi"]->real_escape_string($player["photoURL"])."' WHERE player_id = '".$GLOBALS["db_fi"]->real_escape_string($player["id"])."'";
            $update = $GLOBALS["db_fi"]->query($sql);
         }
      }
   }
}