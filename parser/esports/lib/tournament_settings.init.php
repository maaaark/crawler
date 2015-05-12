<?php

if(isset($_POST["show_table_on_front"])){
	$show_table_on_front = 0;
	if(intval($_POST["show_table_on_front"]) == 1){
		$show_table_on_front = 1;
	}
	$GLOBALS["db_fi"]->query("UPDATE esports_tournament SET show_standings_on_front = '".$show_table_on_front."' WHERE id = '".$tournament["id"]."'");
	addInstantMessage("Die Turnier Einstellungen wurden erfolgreich gespeichert.", "green");
	header("Location: index.php?parser=esports&league=".$data["id"]."&tournament=".$tournament["id"]."&settings");
} else {
	$template = new template;
	$template->load("tournament/settings");
	$template->assign("LEAGUE_ID_INTERN", $data["id"]);
	$template->assign("SITE_TITLE", $tournament["name"]." - ".$data["short_name"]." - Einstellungen");

	foreach($tournament as $column => $value){
		$template->assign(strtoupper($column), $value);
	}

	foreach($data as $column => $value){
		$template->assign("LEAGUE_".strtoupper($column), $value);
	}

	$tmpl = $template->display(true);
	$tmpl = $template->operators();
	echo $tmpl;
}