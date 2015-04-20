<?php

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
$tmpl = $template->display(true);
$tmpl = $template->operators();
echo $tmpl;