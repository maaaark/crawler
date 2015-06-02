<?php

$nums          = $GLOBALS["db_nw"]->num_rows($GLOBALS["db_nw"]->query("SELECT id FROM users"));
$nums_verified = $GLOBALS["db_nw"]->num_rows($GLOBALS["db_nw"]->query("SELECT id FROM users WHERE summoner_veryfied = '1'"));
$last_register = $GLOBALS["db_nw"]->fetch_array($GLOBALS["db_nw"]->query("SELECT created_at FROM users ORDER BY created_at DESC LIMIT 1"));

$template = new template;
$template->load("index");
$template->assign("SITE_TITLE", "Netzwerk Benutzer");

$template->assign("USER_COUNT", $nums);
$template->assign("USER_COUNT_VERIFIED", $nums_verified);
$template->assign("LAST_REGISTER", date("H:i", strtotime($last_register["created_at"]))." Uhr, ".date("d.m.Y", strtotime($last_register["created_at"])));

$tmpl = $template->display(true);
$tmpl = $template->operators();
echo $tmpl;