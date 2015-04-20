<?php
require_once 'system/init.php';
require_once 'system/tmpl_main.class.php';

function error_404(){
   $template = new template;
   $template->load("404_error", true);
   $template->assign("SITE_TITLE", "Seite nicht gefunden");
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}

if(isset($_SESSION["user_id"])){
   if(isset($_GET["logout"])){
      logout();
      addInstantMessage("Erfolgreich ausgeloggt.", "green");
      header("Location: index.php");
   } elseif(isset($_GET["account_settings"])){
      require_once 'system/dashboard_handler/account_settings.page.php';
   } elseif(isset($_GET["logs"])){
      require_once 'system/logs_handler/index.php';
   } else {
      // Parser anzeigen
      if(isset($_GET["parser"])){
         if(file_exists("parser/".trim($_GET["parser"])."/init.php")){
            // Wenn Parser Config existiert gucken ob User-Rollen den Rechten entsprechen um Parser zu nutzen:
            if(file_exists("parser/".trim($_GET["parser"])."/config.json")){
               $parser_config = json_decode(file_get_contents("parser/".trim($_GET["parser"])."/config.json"), true);
               
               if(isset($parser_config["roles"]) && is_array($parser_config["roles"])){
                  if(checkCanSee($_SESSION["roles"], $parser_config["roles"])){
                     $check_status = true;
                  } else {
                     $check_status = false;
                  }
               } else {
                  $check_status = true;
               }
            } else {
               $check_status = true;
            }
         } else {
            $check_status = false;
         }
         
         if($check_status){
            define("CURRENT_MODULE", trim($_GET["parser"]));
            require_once "parser/".trim($_GET["parser"])."/init.php";
         } else {
            error_404();
         }
      }
      elseif(isset($_GET["module"])){
         if(file_exists("parser/".trim($_GET["module"])."/init.php")){
            // Wenn Parser Config existiert gucken ob User-Rollen den Rechten entsprechen um Parser zu nutzen:
            if(file_exists("parser/".trim($_GET["module"])."/config.json")){
               $parser_config = json_decode(file_get_contents("parser/".trim($_GET["module"])."/config.json"), true);
               
               if(isset($parser_config["roles"]) && is_array($parser_config["roles"])){
                  if(checkCanSee($_SESSION["roles"], $parser_config["roles"])){
                     $check_status = true;
                  } else {
                     $check_status = false;
                  }
               } else {
                  $check_status = true;
               }
            } else {
               $check_status = true;
            }
         } else {
            $check_status = false;
         }
         
         if($check_status){
            define("CURRENT_MODULE", trim($_GET["module"]));
            require_once "parser/".trim($_GET["module"])."/init.php";
         } else {
            error_404();
         }
      // Dashboard anzeigen
      } else {
         require_once 'system/dashboard_handler/dashboard.page.php';
      }
   }
} else {
   if(isset($_GET["setAdmin"]) && CAN_SET_ADMIN){
      require_once 'system/login_handler/setadmin.page.php';
   } else {
      require_once 'system/login_handler/login.page.php';
   }
}

?>