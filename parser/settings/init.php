<?php

if(isset($_POST["dashboard_alert"]) && isset($_POST["dashboard_alert_title"])){
   $arr = array();
   $arr["title"]   = trim($_POST["dashboard_alert_title"]);
   $arr["content"] = trim($_POST["dashboard_alert"]);
   $json = json_encode($arr);
   
   $datei = fopen("logs/dashboard_alert.txt","w+");
   rewind($datei);
   fwrite($datei, $json);
   fclose($datei);
   
   addInstantMessage("Dashboard Alert wurde erfolgreich aktualisiert.", "green");
   header("Location: index.php?module=settings");
} else {
   $dashboard_alert_title = "";
   $dashboard_alert       = "";
   if(file_exists("logs/dashboard_alert.txt")){
      $dashboard_alert_data = file_get_contents("logs/dashboard_alert.txt");
      $dashboard_alert_data = json_decode($dashboard_alert_data, true);
      if(isset($dashboard_alert_data["title"]) && trim($dashboard_alert_data["title"]) != ""){
         $dashboard_alert_title = trim($dashboard_alert_data["title"]);
      }
      
      if(isset($dashboard_alert_data["content"]) && trim($dashboard_alert_data["content"]) != ""){
         $dashboard_alert = trim($dashboard_alert_data["content"]);
      }
   }
   
   $template = new template;
   $template->load("index");
   $template->assign("SITE_TITLE", "Einstellungen");
   $template->assign("DASHBOARD_ALERT", $dashboard_alert);
   $template->assign("DASHBOARD_ALERT_TITLE", $dashboard_alert_title);
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}