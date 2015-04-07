<?php

require_once 'parser/urf_matches/urf_matchID.class.php';

if(isset($_GET["normal_parser"])){
   if(isset($_GET["run"])){
      require_once 'parser/urf_matches/parser.init.php';
   } else {
      $template = new template;
      $template->load("normal_parser");
      $template->assign("SITE_TITLE", "URF-Matches Parser");
      $tmpl = $template->display(true);
      $tmpl = $template->operators();
      echo $tmpl;
   }
} elseif(isset($_GET["reverse_parser"])){
   if(isset($_GET["run"])){
      require_once 'parser/urf_matches/parser_reverse.init.php';
   } else {
      $template = new template;
      $template->load("reverse_parser");
      $template->assign("SITE_TITLE", "URF-Matches Parser");
      $tmpl = $template->display(true);
      $tmpl = $template->operators();
      echo $tmpl;
   }
} else {
   $last = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT * FROM urf_matchIDs ORDER BY added DESC LIMIT 1"));
   $nums = $GLOBALS["db"]->num_rows($GLOBALS["db"]->query("SELECT * FROM urf_matchIDs"));
   
   $template = new template;
   $template->load("index");
   $template->assign("SITE_TITLE", "URF-Matches Parser");
   $template->assign("NUMS_ALL", number_format($nums, 0, ",", "."));
   $template->assign("LAST_MATCHID", $last["matchID"]);
   $template->assign("LAST_MATCHID_DATE", $last["date"]);
   $template->assign("LAST_MATCHID_ADDED", $last["added"]);
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}