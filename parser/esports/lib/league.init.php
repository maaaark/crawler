<?php

if(isset($_GET["loadtournaments"])){
   include dirname(__FILE__).'/league_load_tournaments.init.php';
} elseif(isset($_GET["tournament"])){
   $tournament = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_tournament WHERE id = '".$GLOBALS["db_fi"]->real_escape_string($_GET["tournament"])."' AND league_id = '".$GLOBALS["db_fi"]->real_escape_string($data["league_id"])."'"));
   if(isset($tournament["id"]) && $tournament["id"] > 0){
      include dirname(__FILE__).'/league_tournament.init.php';
   } else {
      error_404();
   }
   
} else {
   $tournaments_list = "";
   $query = $GLOBALS["db_fi"]->query("SELECT * FROM esports_tournament WHERE league_id = '".$GLOBALS["db_fi"]->real_escape_string($data["league_id"])."' ORDER BY tournament_id  DESC");
   while($row = $GLOBALS["db_fi"]->fetch_object($query)){
      $template = new template;
      $template->load("league/tournament_list_element");
      foreach((array)$row as $column => $value){
         $template->assign("TOURNAMENT_".strtoupper($column), $value);
      }
      foreach($data as $column => $value){
         $template->assign("LEAGUE_".strtoupper($column), $value);
      }
      
      if($data["default_tournament"] == $row->tournament_id){
         $template->assign("HIGHLIGHT", "TRUE");
      }
      $tournaments_list .= $template->display();
   }
   
   $template = new template;
   $template->load("league/index");
   foreach($data as $column => $value){
      $template->assign(strtoupper($column), $value);
   }
   $template->assign("SITE_TITLE", $data["short_name"]);
   $template->assign("TOURNAMENTS_LIST", $tournaments_list);
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}