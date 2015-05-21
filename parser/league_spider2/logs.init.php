<?php

if(isset($_GET["error_log"])){

} else {
   $template = new template;
   $template->load("logs");

   $success_log = "Es ist kein Success-Log vorhanden";
   if(file_exists("logs/league_spider/success.log.txt")){
      $success_log = file_get_contents("logs/league_spider/success.log.txt");
   }

   $template->assign("SITE_TITLE", "League Spider Logs");
   $template->assign("SUCCESS_LOG", $success_log);
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}