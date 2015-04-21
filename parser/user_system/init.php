<?php

if(isset($_GET["add_edit"])){
   require_once dirname(__FILE__).'/add_edit.init.php';
} else {
   $query      = $GLOBALS["db"]->query("SELECT * FROM users ORDER BY username ASC");
   $users_list = "";
   while($row = $GLOBALS["db"]->fetch_object($query)){
      $template = new template;
      $template->load("list_element");
      foreach((array) $row as $column => $value){
         $template->assign(strtoupper($column), $value);
      }
      $users_list .= $template->display();
   }

   $template = new template;
   $template->load("index");
   $template->assign("USERS_LIST", $users_list);
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}