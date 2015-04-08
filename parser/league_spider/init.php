<?php
require_once dirname(__FILE__).'/lib/config.php';

$template = new template;
$template->load("index");
$template->assign("LEAGUE_SPIDER_GAME_VERSION", GAME_VERSION);

$matches_nums = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT COUNT(*) FROM lol_league_parser_matches WHERE patch = '".GAME_VERSION."'"));
$template->assign("MATCHES_CURRENT_PATCH", number_format($matches_nums["COUNT(*)"], 0, ",", "."));

$summoner_num = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT COUNT(*) FROM lol_league_parser_summoner"));
$template->assign("POSSIBLE_SUMMONERS", number_format($summoner_num["COUNT(*)"], 0, ",", "."));

$champs_nums = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT COUNT(*) FROM lol_champions_stats WHERE patch = '".GAME_VERSION."'"));
$template->assign("CHAMPIONS_CURRENT_PATCH", number_format($champs_nums["COUNT(*)"], 0, ",", "."));

$last_crawling_mins   = "never";
$last_crawling 	 	  = "";
$last_crawling_status = "red";
if(file_exists("logs/league_spider/last_update.log.txt")){
	$last_crawling = file_get_contents("logs/league_spider/last_update.log.txt");
	$diff    = abs(strtotime($last_crawling) - strtotime(date('Y-m-d H:i:s')));
	$last_crawling_mins    = floor($diff / 60);

	if($last_crawling_mins < 15 && $last_crawling_mins > 5 && $last_crawling_mins != "never"){
		$last_crawling_status = "orange";
	}
	elseif($last_crawling_mins <= 5){
		$last_crawling_status = "green";
	}
}
$template->assign("LAST_CRAWLING_STATUS", $last_crawling_status);
$template->assign("LAST_CRAWLING_MINS", $last_crawling_mins);
$template->assign("LAST_CRAWLING", date("H:i:s d.m.Y", strtotime($last_crawling)));

$template->assign("SITE_TITLE", "League Spider");
$tmpl = $template->display(true);
$tmpl = $template->operators();
echo $tmpl;