<?php
   if(isset($data["league_tournaments"]) && trim($data["league_tournaments"]) != "" && trim(str_replace(" ", "", $data["league_tournaments"])) != "[]"){
      $count  = 0;
      $tournaments = json_decode($data["league_tournaments"], true);
      foreach($tournaments as $tournament){
         $content = @file_get_contents("http://na.lolesports.com:80/api/tournament/".trim($tournament).".json");
         if($content){
            $json = json_decode($content, true);
            //echo "<pre>", print_r($json), "</pre>";
            
            if(isset($json["name"]) && isset($json["namePublic"])){
               $count++;
               $check = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_tournament WHERE tournament_id = '".$GLOBALS["db_fi"]->real_escape_string($tournament)."'"));
               
               if(isset($check["id"]) && $check["id"] > 0){
                  $sql      = "UPDATE esports_tournament SET ";
                  $sql_type = "update";
               } else {
                  $sql      = "INSERT INTO esports_tournament SET tournament_id = '".$GLOBALS["db_fi"]->real_escape_string($tournament)."', ";
                  $sql_type = "insert";
               }
               
               $sql .= "name = '".$GLOBALS["db_fi"]->real_escape_string($json["name"])."'";
               $sql .= ", name_public = '".$GLOBALS["db_fi"]->real_escape_string($json["namePublic"])."'";
               
               $is_finished = 0;
               if(isset($json["isFinished"]) && $json["isFinished"] == 1){
                  $is_finished = 1;
               }
               $sql .= ", is_finished = '".$GLOBALS["db_fi"]->real_escape_string($is_finished)."'";
               
               $published_riot = 0;
               if(isset($json["published"]) && $json["published"] == 1){
                  $published_riot = 1;
               }
               $sql .= ", published_riot = '".$GLOBALS["db_fi"]->real_escape_string($published_riot)."'";
               
               $winner = 0;
               if(isset($json["winner"]) && $json["winner"] > 0){
                  $winner = $json["winner"];
               }
               $sql .= ", winner = '".$GLOBALS["db_fi"]->real_escape_string($winner)."'";
               
               $no_vods = 0;
               if(isset($json["noVods"]) && $json["noVods"] == 1){
                  $no_vods = 1;
               }
               $sql .= ", no_vods = '".$GLOBALS["db_fi"]->real_escape_string($no_vods)."'";
               
               $season = "";
               if(isset($json["season"]) && trim($json["season"]) != ""){
                  $season = $json["season"];
               }
               $sql .= ", season = '".$GLOBALS["db_fi"]->real_escape_string($season)."'";
               
               $date_begin = "0000-00-00 00:00:00";
               if(isset($json["dateBegin"]) && trim($json["dateBegin"]) != ""){
                  $date_begin = date("Y-m-d H:i:s", strtotime($json["dateBegin"]));
               }
               $sql .= ", date_begin = '".$GLOBALS["db_fi"]->real_escape_string($date_begin)."'";
               
               $date_end = "0000-00-00 00:00:00";
               if(isset($json["dateEnd"]) && trim($json["dateEnd"]) != ""){
                  $date_end = date("Y-m-d H:i:s", strtotime($json["dateEnd"]));
               }
               $sql .= ", date_end = '".$GLOBALS["db_fi"]->real_escape_string($date_end)."'";
               $sql .= ", league_id = '".$GLOBALS["db_fi"]->real_escape_string($data["league_id"])."'";
               
               if($sql_type == "update"){
                  $sql .= " WHERE id = '".$GLOBALS["db_fi"]->real_escape_string($check["id"])."'";
               }
               $GLOBALS["db_fi"]->query($sql);
               
            } else {
               addInstantMessage("Das Turnier mit der ID ".$tournament." konnte aufgrund von unbrauchbarem API-Response nicht aktualisiert werden.", "orange");
            }
         } else {
            addInstantMessage("Das Turnier mit der ID ".$tournament." konnte aufgrund von einem API Fehler nicht aktualisiert werden.", "orange");
         }
      }
      
      if($count == 0){
         addInstantMessage("Es wurden aufgrund von Riot Esports API Fehlern keine Daten aktualisiert.", "red");
      } else {
         addInstantMessage("Es wurden erfolgreich  ".$count." Turniere der Liga ".$data["label"]." aktualisiert.", "green");
      }
      header("Location: index.php?parser=esports&league=".trim($data["id"]));
   } else {
      addInstantMessage("Es wurden f&uuml;r diese Liga noch keine Turnier-IDs gespeichert (Ligen bitte aktualisieren).", "red");
      header("Location: index.php?parser=esports&league=".trim($data["id"]));
   }