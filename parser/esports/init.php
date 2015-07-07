<?php

if(isset($_GET["league"])){
   $data = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_league WHERE id = '".$GLOBALS["db_fi"]->real_escape_string($_GET["league"])."'"));
   if(isset($data["id"]) && $data["id"] > 0){
      require_once dirname(__FILE__).'/lib/league.init.php';
   } else {
      error_404();
   }
} elseif(isset($_GET["loadteamids"])){
   require_once dirname(__FILE__).'/loadteamids.init.php';
} elseif(isset($_GET["teams_overview"])){
   require_once dirname(__FILE__).'/teams_overview.init.php';
} elseif(isset($_GET["loadleagues"])){
   require_once dirname(__FILE__).'/loadleagues.init.php';
} elseif(isset($_GET["teams"])){
   require_once dirname(__FILE__).'/teams.init.php';
   } elseif(isset($_GET["statistics"])){
   require_once dirname(__FILE__).'/statistics.init.php';
} elseif(isset($_GET["updateTournamentIDs"])){
   $content = @file_get_contents("http://na.lolesports.com:80/api/league.json?parameters%5Bmethod%5D=all");

   if($content){
      $json = json_decode($content, true);
      if(isset($json["leagues"]) && is_array($json["leagues"])){
         $update_count = 0;
         $league_slugs = array();
         $query   = $GLOBALS["db_fi"]->query("SELECT * FROM leagues ORDER BY name");
         while($row = $GLOBALS["db"]->fetch_object($query)){
            if(isset($row->slug) && trim($row->slug) != ""){
               $league_slugs[$row->id] = trim(strtoupper($row->slug));
            }
         }

         foreach($json["leagues"] as $league){
            if(isset($league["shortName"])){
               foreach($league_slugs as $tournament_id => $slug){
                  $check1 = strtoupper(trim($slug));
                  $check2 = strtoupper(trim($league["shortName"]));

                  $check1 = str_replace(" ", "_", str_replace("-", "_", $check1));
                  $check2 = str_replace(" ", "_", str_replace("-", "_", $check2));
                  if($check1 == $check2){
                     $GLOBALS["db_fi"]->query("UPDATE leagues SET tournament_id = '".$league["defaultTournamentId"]."' WHERE id = '".$tournament_id."'");
                     $update_count++;
                  }
               }
            }
         }

         if($update_count > 0){
            addInstantMessage("Es wurden <b>".$update_count."</b> Turnier IDs erfolgreich aktualisiert.", "green");
         } else {
            addInstantMessage("Es gab keine neuen Turniere deren IDs aktualisiert werden mussten.", "orange");
         }
      } else {
         addInstantMessage("Response der API war nicht vearbeitbar.", "red");
      }
   } else {
      addInstantMessage("Esports API hat keinen Content zur&uuml;ckgegeben.", "red");
   }
   header("Location: index.php?parser=esports");
} elseif(isset($_GET["edit_league"])){
   require_once dirname(__FILE__).'/edit_league.init.php';
} else {
   $leagues = "";
   $query   = $GLOBALS["db_fi"]->query("SELECT * FROM esports_league ORDER BY short_name ASC");
   while($row = $GLOBALS["db"]->fetch_object($query)){
      $tmpl = new template;
      $tmpl->load("leagues_list");
      foreach((array) $row as $column => $value){
         $tmpl->assign(strtoupper($column), $value);
      }
      $leagues .= $tmpl->display();
   }

   $template = new template;
   $template->load("index");
   $template->assign("SITE_TITLE", "Esports Parser");
   $template->assign("LEAGUES_LIST", $leagues);
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}