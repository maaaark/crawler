<?php

if(isset($_GET["start"])){
	$start = 0;
	if($_GET["start"] > 0){
		$start = $_GET["start"];
	}
	$limit = 10;

	$log = "";
	for($i = $start; $i < $start + $limit; $i++){
		$content = @file_get_contents("http://na.lolesports.com:80/api/team/".trim($i).".json");
		if($content){
			$json = json_decode($content, true);
			if(isset($json["name"])){
				if(!isset($json["acronym"]) || $json["acronym"] == null){
					$json["acronym"] = "";
				}

				$check = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM teams WHERE shorthandle LIKE '".$GLOBALS["db_fi"]->real_escape_string($json["acronym"])."'
																										AND shorthandle != ''
					                                                                                     OR name LIKE '".$GLOBALS["db_fi"]->real_escape_string($json["name"])."' LIMIT 1"));
				if(isset($check["id"]) && $check["id"] > 0){
					$GLOBALS["db_fi"]->query("UPDATE teams SET team_id_riot = '".$i."' WHERE id = '".$check["id"]."'");
					$log .= '<div class="message green"><b>'.$check["name"].'</b> wurde erfolgreich aktualisiert.</div>';
				} else {
					$log .= '<div class="message orange">Kein Match f&uuml;r die Team-ID '.$i.' gefunden.</div>';
				}
			} else {
				$log .= '<div class="message red">Nicht verwertbaren JSON-Code erhalten:<div style="font-size:12px;opacity:0.5">http://na.lolesports.com:80/api/team/'.trim($i).'.json</div></div>';
			}
		} else {
			$log .= '<div class="message red">Unbekannter API Error<div style="font-size:12px;opacity:0.5">http://na.lolesports.com:80/api/team/'.trim($i).'.json</div></div>';
		}
	}

	$template = new template;
	$template->load("loadteamids_parser");
	$template->assign("SITE_TITLE", "Esports Team IDs aktualisieren");
	$template->assign("LOG", $log);
	$template->assign("NEXT_START", ($start + $limit - 1));
	$tmpl = $template->display(true);
	$tmpl = $template->operators();
	echo $tmpl;
} else {
	$template = new template;
	$template->load("loadteamids_index");
	$template->assign("SITE_TITLE", "Esports Team IDs aktualisieren");
	$tmpl = $template->display(true);
	$tmpl = $template->operators();
	echo $tmpl;
}