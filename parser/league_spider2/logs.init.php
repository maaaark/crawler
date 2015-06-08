<?php

if(isset($_GET["error_logs"])){
    if(isset($_GET["log_name"])){
        if(isset($_GET["delete"])){
            if(isset($_POST["log_name"]) && trim($_GET["log_name"]) == trim($_POST["log_name"])){
                $status = false;
                if(file_exists("logs/league_spider/error_logs/".trim($_GET["log_name"]))){
                    $status = unlink("logs/league_spider/error_logs/".trim($_GET["log_name"]));
                }
                
                if($status){
                    addInstantMessage("Error-Log Datei erfolgreich gel&ouml;scht!", "green");
                } else {
                    addInstantMessage("Error-Log Datei konnte nicht gel&ouml;scht werden. Entweder existiert die Datei nicht oder es fehlen Schreib-/L&ouml;schrechte.", "red");
                }
                header("Location: index.php?parser=league_spider2&logger&error_logs");
            } else {
                $template = new template;
                $template->load("error_logs/delete");
                $template->assign("SITE_TITLE", "Error-Log entfernen");
                
                $template->assign("NAME", $_GET["log_name"]);
                $name_transform = trim(str_replace(".log.txt", "", str_replace("error.", "", trim($_GET["log_name"]))));
                $date           = date("d.m.Y", strtotime(str_replace("_", "-", substr($name_transform, 0, strpos($name_transform, "__")))));
                $time           = date("H:i:s", strtotime(str_replace("_", ":", substr($name_transform, strpos($name_transform, "__") + 2))));
                $template->assign("NAME_TRANSFORM", $name_transform);
                $template->assign("DATE", $date);
                $template->assign("TIME", $time);
                $tmpl = $template->display(true);
                $tmpl = $template->operators();
                echo $tmpl;
            }
        } else {
            if(file_exists("logs/league_spider/error_logs/".trim($_GET["log_name"]))){
                $file = file_get_contents("logs/league_spider/error_logs/".trim($_GET["log_name"]));
                $json = json_decode($file, true);
                
                $template = new template;
                $template->load("error_logs/detail");
                $template->assign("FILE", $file);
                $template->assign("NAME", $_GET["log_name"]);
                
                $name_transform = trim(str_replace(".log.txt", "", str_replace("error.", "", trim($_GET["log_name"]))));
                $date           = date("d.m.Y", strtotime(str_replace("_", "-", substr($name_transform, 0, strpos($name_transform, "__")))));
                $time           = date("H:i:s", strtotime(str_replace("_", ":", substr($name_transform, strpos($name_transform, "__") + 2))));
                $template->assign("NAME_TRANSFORM", $name_transform);
                $template->assign("DATE", $date);
                $template->assign("TIME", $time);
                $template->assign("SITE_TITLE", "Error Log ansehen");
                $tmpl = $template->display(true);
                $tmpl = $template->operators();
                echo $tmpl;
            } else {
                error_404();
            }
        }
    } else {
        $list = "";
        $arr  = array();
        $dir  = opendir("logs/league_spider/error_logs/");
        while($folder = readdir($dir)){
            if(trim($folder) != "." && trim($folder) != ".." && trim($folder) != ""){
                $arr[] = $folder;
            }
        }
        asort($arr);
        
        foreach($arr as $folder){
            $template = new template;
            $template->load("error_logs/list_element");
            $template->assign("NAME", trim($folder));
            
            $name_transform = trim(str_replace(".log.txt", "", str_replace("error.", "", trim($folder))));
            $date           = date("d.m.Y", strtotime(str_replace("_", "-", substr($name_transform, 0, strpos($name_transform, "__")))));
            $time           = date("H:i:s", strtotime(str_replace("_", ":", substr($name_transform, strpos($name_transform, "__") + 2))));
            $template->assign("NAME_TRANSFORM", $name_transform);
            $template->assign("DATE", $date);
            $template->assign("TIME", $time);
            $list .= $template->display();
        }
        
        $template = new template;
        $template->load("error_logs/index");
        $template->assign("SITE_TITLE", "League Spider Error-Logs");
        $template->assign("LIST", $list);
        $tmpl = $template->display(true);
        $tmpl = $template->operators();
        echo $tmpl;
    }
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