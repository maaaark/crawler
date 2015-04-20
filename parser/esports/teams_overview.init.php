<?php

if(isset($_GET["edit"])){
   $data = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM teams WHERE id = '".$GLOBALS["db_fi"]->real_escape_string($_GET["edit"])."'"));
   
   if(isset($data["id"]) && $data["id"] > 0){
      if(isset($_POST["id"]) && $_POST["id"] == $data["id"]){
         $update = $GLOBALS["db_fi"]->query("UPDATE teams SET name         = '".$GLOBALS["db_fi"]->real_escape_string($_POST["name"])."',
                                                              region       = '".$GLOBALS["db_fi"]->real_escape_string($_POST["region"])."',
                                                              team_id_riot = '".$GLOBALS["db_fi"]->real_escape_string($_POST["team_id_riot"])."'
                                                        WHERE id = '".$data["id"]."'");
         addInstantMessage("Das Team wurde erfolgreich bearbeitet.", "green");
         header("Location: index.php?parser=esports&teams_overview");
      } else {
         $template = new template;
         $template->load("teams/edit");
         foreach($data as $column => $value){
            $template->assign(strtoupper($column), $value);
         }
         $template->assign("SITE_TITLE", "Team bearbeiten");
         $tmpl = $template->display(true);
         $tmpl = $template->operators();
         echo $tmpl;
      }
   } else {
      error_404();
   }
} else {
   $query = $GLOBALS["db_fi"]->query("SELECT * FROM teams ORDER BY region ASC, name ASC");


   $list = "";
   while($row = $GLOBALS["db_fi"]->fetch_object($query)){
      $template = new template;
      $template->load("teams/list_element");
      foreach((array) $row as $column => $value){
         $template->assign(strtoupper($column), $value);
      }
      $template->assign("REGION_UPPER", strtoupper($row->region));
      $list .= $template->display();
   }

   $template = new template;
   $template->load("teams/overview");
   $template->assign("LIST", $list);
   $template->assign("SITE_TITLE", "Esports Teams &Uuml;bersicht");
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}