<?php
require_once 'system/init.php';
require_once 'system/tmpl_main.class.php';

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
      if(isset($_GET["parser"])){
         if(file_exists("parser/".trim($_GET["parser"])."/init.php")){
            define("CURRENT_MODULE", trim($_GET["parser"]));
            require_once "parser/".trim($_GET["parser"])."/init.php";
         } else {
            $template = new template;
            $template->load("404_error");
            $tmpl = $template->display(true);
            $tmpl = $template->operators();
            echo $tmpl;
         }
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