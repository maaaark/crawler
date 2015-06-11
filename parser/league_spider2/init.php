<?php
require_once dirname(__FILE__).'/lib/config.php';

if(isset($_GET["settings"])){
   require_once dirname(__FILE__).'/settings.init.php';
} elseif(isset($_GET["logger"])){
   require_once dirname(__FILE__).'/logs.init.php';
} else {
	require_once dirname(__FILE__).'/overview_functions.php';

   $template = new template;
   $template->load("index");
   $template->assign("LEAGUE_SPIDER_GAME_VERSION", GAME_VERSION);

   $matches_nums        = 0;
   $matches_nums_euw    = 0;
   $matches_nums_na     = 0;
   $matches_nums_eune   = 0;
   $matches_count_query = $GLOBALS["db"]->query("SELECT matches_count, region FROM lol_champions_stats WHERE patch = '".GAME_VERSION."'");
   while($row = $GLOBALS["db"]->fetch_object($matches_count_query)){
      $matches_nums = $matches_nums + ($row->matches_count / 10);
      if($row->region == "euw"){
         $matches_nums_euw = $matches_nums_euw + ($row->matches_count / 10);
      }
      elseif($row->region == "na"){
         $matches_nums_na = $matches_nums_na + ($row->matches_count / 10);
      }
      elseif($row->region == "eune"){
         $matches_nums_eune = $matches_nums_eune + ($row->matches_count / 10);
      }
   }
   $template->assign("MATCHES_CURRENT_PATCH", number_format($matches_nums, 0, ",", "."));
   $template->assign("MATCHES_COUNT_EUW", number_format($matches_nums_euw, 0, ",", "."));
   $template->assign("MATCHES_COUNT_NA", number_format($matches_nums_na, 0, ",", "."));
   $template->assign("MATCHES_COUNT_EUNE", number_format($matches_nums_eune, 0, ",", "."));

   $summoner_num = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT COUNT(*) FROM lol_league_parser_summoner"));
   $template->assign("POSSIBLE_SUMMONERS", number_format($summoner_num["COUNT(*)"], 0, ",", "."));
   
   $summoner_num_unparsed = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT COUNT(*) FROM lol_league_parser_summoner WHERE last_update < '".date("Y-m-d H:i:s", time() - (60*SUMMONER_UPDATE_WAITING))."'"));
   $template->assign("POSSIBLE_SUMMONERS_UNPARSED", number_format($summoner_num_unparsed["COUNT(*)"], 0, ",", "."));

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
   
   // Champions-List
   function add_durchschnitt($array, $column, $value){
      $temp = 0;
      if(isset($array["data"][$column])){
         $temp = $array["data"][$column];
      }
      $array["data"][$column] = ($temp + $value) / $array["count"];
      return $array;
   }

   function add_normal($array, $column, $value){
      $temp = 0;
      if(isset($array["data"][$column])){
         $temp = $array["data"][$column];
      }
      $array["data"][$column] = $temp + $value;
      return $array;
   }

   $query = $GLOBALS["db"]->query("SELECT * FROM lol_champions_stats WHERE patch = '".GAME_VERSION."' ORDER BY matches_count DESC");
   $champion_arr   = array();
   $champions_list = "";
   while($row = $GLOBALS["db"]->fetch_object($query)){
      if(!isset($champion_arr[$row->champion]) || !is_array($champion_arr[$row->champion])){
         $champion_arr[$row->champion] = array("data" => array(), "count" => 0);
      }

      $champion_arr[$row->champion]["count"]++;
      $champion_arr[$row->champion] = add_durchschnitt($champion_arr[$row->champion], "kills", $row->kills);
      $champion_arr[$row->champion] = add_durchschnitt($champion_arr[$row->champion], "deaths", $row->deaths);
      $champion_arr[$row->champion] = add_durchschnitt($champion_arr[$row->champion], "assists", $row->assists);
      $champion_arr[$row->champion] = add_durchschnitt($champion_arr[$row->champion], "lasthits", $row->lasthits);
      $champion_arr[$row->champion] = add_durchschnitt($champion_arr[$row->champion], "gold_earned", $row->gold_earned);

      $champion_arr[$row->champion] = add_normal($champion_arr[$row->champion], "matches_count", $row->matches_count);
      $champion_arr[$row->champion] = add_normal($champion_arr[$row->champion], "wins", $row->wins);
   }

   foreach($champion_arr as $champion_id => $champion){
      $tmpl = new Template;
      $tmpl->load("list_element");
      foreach($champion["data"] as $column => $value){
         $tmpl->assign(strtoupper($column), $value);
      }
      
      $champ = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM champions WHERE champion_id = '".$GLOBALS["db"]->real_escape_string($champion_id)."'"));
      if(isset($champ["id"]) && $champ["id"] > 0){
         foreach($champ as $column => $value){
            $tmpl->assign("CHAMPION_".strtoupper($column), $value);
         }
      }
      $tmpl->assign("KILLS_ROUND", format_number(round($champion["data"]["kills"], 1)));
      $tmpl->assign("DEATHS_ROUND", format_number(round($champion["data"]["deaths"], 1)));
      $tmpl->assign("ASSISTS_ROUND", format_number(round($champion["data"]["assists"], 1)));
      $tmpl->assign("LASTHITS_ROUND", format_number(round($champion["data"]["lasthits"], 1)));
      $tmpl->assign("GOLD_EARNED_ROUND", format_number(round($champion["data"]["gold_earned"], 1)));
      
      $tmpl->assign("WINRATE", str_replace(".", ",", round(($champion["data"]["wins"] / $champion["data"]["matches_count"] * 100), 1)));
      $champions_list .= $tmpl->display();
   }
   $template->assign("CHAMPIONS_LIST", $champions_list);
   
   $template->assign("RUNNING_CRAWLER_COUNT", getRunningCount());
   $template->assign("RUNNING_CRAWLER_EUW", getRunningRegion("euw"));
   $template->assign("RUNNING_CRAWLER_NA", getRunningRegion("na"));
   $template->assign("RUNNING_CRAWLER_EUNE", getRunningRegion("eune"));
   
   if(file_exists("logs/league_spider/running/queue_mode_running.txt")){
      $template->assign("QUEUE_MODE_RUNNING", "TRUE");
   }
   
   $template->assign("SITE_TITLE", "League Spider &Uuml;bersicht");
   $tmpl = $template->display(true);
   $tmpl = $template->operators();
   echo $tmpl;
}