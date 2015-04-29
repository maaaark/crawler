<?php

$content = @file_get_contents("http://na.lolesports.com:80/api/league.json?parameters%5Bmethod%5D=all");
if($content){
   $json = json_decode($content, true);
   if(isset($json["leagues"]) && is_array($json["leagues"])){
      foreach($json["leagues"] as $league){
         $check = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT id FROM esports_league WHERE league_id = '".$GLOBALS["db_fi"]->real_escape_string($league["id"])."'"));
         
         if(isset($check["id"]) && $check["id"] > 0){
            $sql = "UPDATE esports_league SET ";
            $sql_type = "update";
         } else {
            $sql = "INSERT INTO esports_league SET league_id           = '".$GLOBALS["db_fi"]->real_escape_string($league["id"])."', ";
            $sql_type = "insert";
         }
         
         $sql .= "short_name          = '".$GLOBALS["db_fi"]->real_escape_string($league["shortName"])."'";
         $sql .= ", default_tournament  = '".$GLOBALS["db_fi"]->real_escape_string($league["defaultTournamentId"])."'";
         $sql .= ", default_series      = '".$GLOBALS["db_fi"]->real_escape_string($league["defaultSeriesId"])."'";
         
         $league_tournaments = "";
         if(isset($league["leagueTournaments"]) && is_array($league["leagueTournaments"])){
            $league_tournaments = json_encode($league["leagueTournaments"]);
         }
         $sql .= ", league_tournaments = '".$GLOBALS["db_fi"]->real_escape_string($league_tournaments)."'";
         
         $riot_url = "";
         if(isset($league["url"]) && trim($league["url"]) != ""){
            $riot_url = $league["url"];
         }
         $sql .= ", riot_url = '".$GLOBALS["db_fi"]->real_escape_string($riot_url)."'";
         
         $label = "";
         if(isset($league["label"]) && trim($league["label"]) != ""){
            $label = $league["label"];
         }
         $sql .= ", label = '".$GLOBALS["db_fi"]->real_escape_string($label)."'";
         
         $published_riot = 0;
         if(isset($league["published"]) && $league["published"] == 1){
            $published_riot = 1;
         }
         $sql .= ", published_riot = '".$published_riot."'";
         
         // "<pre>", print_r($league), "</pre>";
         if($sql_type == "update"){
            $sql .= " WHERE id = '".$GLOBALS["db_fi"]->real_escape_string($check["id"])."'";
         }
         $GLOBALS["db_fi"]->query($sql);
      }   
      addInstantMessage("Die Ligen wurden erfolgreich aktualisiert.", "green");
      header("Location: index.php?parser=esports");
      
   } else {
      addInstantMessage("Die Riot Esports API hat ein ung&uuml;ltiges Ergebnis zur&uuml;ckgegeben.", "red");
      header("Location: index.php?parser=esports");
   }
} else {
   addInstantMessage("Riot Esports API gerade nicht erreichbar.", "red");
   header("Location: index.php?parser=esports");
}

?>