<?php

$cronjob_log = "Kein Log vorhanden";
if(file_exists("logs/cronjob.log.txt")){
	$cronjob_log = file_get_contents("logs/cronjob.log.txt");
}

$template = new Template;
$template->load("logs/index");
$template->assign("CRONJOB_LOG", $cronjob_log);
$template->assign("SITE_TITLE", "Logs &Uuml;bersicht");
$tmpl = $template->display(true);
$tmpl = $template->operators();
echo $tmpl;