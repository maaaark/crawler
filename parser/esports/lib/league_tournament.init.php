<?php

if(isset($_GET["loadschedule"])){
   include dirname(__FILE__).'/tournament_load_schedule.init.php';
} elseif(isset($_GET["settings"])){
   include dirname(__FILE__).'/tournament_settings.init.php';
} elseif(isset($_GET["loadmatch"])){
   include dirname(__FILE__).'/tournament_load_match.init.php';
} else {
   $standings = "";
   $query     = $GLOBALS["db_fi"]->query("SELECT * FROM esports_standings WHERE tournament_id = '".$GLOBALS["db_fi"]->real_escape_string($tournament["tournament_id"])."' ORDER by rank ASC");
   while($row = $GLOBALS["db_fi"]->fetch_object($query)){
      $template = new template;
      $template->load("tournament/standings_element");
      foreach((array) $row as $column => $value){
         $template->assign(strtoupper($column), $value);
      }
      $team = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_team WHERE team_id = '".$row->team_id."'"));
      foreach($team as $column => $value){
         $template->assign("TEAM_".strtoupper($column), $value);
      }
      $standings .= $template->display();
   }

   $matches_list = "";
   $query        = $GLOBALS["db_fi"]->query("SELECT * FROM esports_match WHERE tournament_id = '".$GLOBALS["db_fi"]->real_escape_string($tournament["tournament_id"])."'");
   while($row = $GLOBALS["db_fi"]->fetch_array($query)){
      $template = new template;
      $template->load("tournament/matches_list_element");
      foreach((array) $row as $column => $value){
         $template->assign(strtoupper($column), $value);
      }
      
      foreach($tournament as $column => $value){
         $template->assign("TOURNAMENT_".strtoupper($column), $value);
      }
      
      foreach($data as $column => $value){
         $template->assign("LEAGUE_".strtoupper($column), $value);
      }
      $template->assign("LEAGUE_ID_INTERN", $data["id"]);
      $template->assign("TOURNAMENT_ID_INTERN", $tournament["id"]);
      
      if(!isset($row["winner"]) || $row["winner"] == false || $row["winner"] == null || $row["winner"] == 0){
         $template->assign("NO_WINNER", "TRUE");
      }
      $matches_list .= $template->display();
   }
   
   $template = new template;
   $template->load("tournament/index");
   
   foreach($tournament as $column => $value){
      $template->assign(strtoupper($column), $value);
   }
   
   foreach($data as $column => $value){
      $template->assign("LEAGUE_".strtoupper($column), $value);
   }
   
   $template->assign("STANDINGS", $standings);
   $template->assign("LEAGUE_ID_INTERN", $data["id"]);
   $template->assign("SITE_TITLE", $tournament["name"]." - ".$data["short_name"]);
   $template->assign("MATCHES_LIST", $matches_list);
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}