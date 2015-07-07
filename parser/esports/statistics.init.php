<?php

if(isset($_GET["tournament"])){
    if(isset($_GET["team"])){

    } else {
        $tournament = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_tournament WHERE tournament_id = '".$GLOBALS["db_fi"]->real_escape_string(trim($_GET["tournament"]))."' ORDER BY id DESC"));
        $league     = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_league WHERE league_id = '".$GLOBALS["db_fi"]->real_escape_string(trim($tournament["league_id"]))."' ORDER BY id DESC"));
        $template = new template;
        $template->load("statistics/tournament");
        foreach($tournament as $column => $value){
            $template->assign(strtoupper($column), $value);
        }
        foreach($league as $column => $value){
            $template->assign("LEAGUE_".trim(strtoupper($column)), $value);
        }
        
        function durchschnitt($alt, $neu){
            return ($neu + $alt) / 2;
        }
        
        $tournament_round = 1;
        $matches_count    = 0;
        $games_count      = 0;
        $champions = array();
        $matches   = $GLOBALS["db_fi"]->query("SELECT * FROM esports_match WHERE tournament_id = '".$GLOBALS["db_fi"]->real_escape_string(trim($tournament["tournament_id"]))."' AND winner > 0");
        while($match = $GLOBALS["db_fi"]->fetch_object($matches)){
            $matches_count++;
            if(isset($match->tournament_round)){
                $tournament_round = $match->tournament_round;
            }
            
            $games = json_decode($match->games, true);
            foreach($games as $game_id){
                $games_count++;
                $game        = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_game WHERE game_id = '".$GLOBALS["db_fi"]->real_escape_string($game_id)."' AND winner > 0 ORDER BY id ASC"));
                $player_data = json_decode($game["players"], true);
                if(isset($player_data) && is_array($player_data)){
                    foreach($player_data as $player_name => $player){
                        if(isset($champions[$player["championId"]])){
                            $champions[$player["championId"]]["count"]++;
                            
                            if($player["teamId"] == $game["winner"] && $game["winner"] > 0){
                                $champions[$player["championId"]]["wins"]++;
                            }
                            $champions[$player["championId"]]["lasthits"] = durchschnitt($champions[$player["championId"]]["lasthits"], $player["minionsKilled"]);
                            $champions[$player["championId"]]["gold"] = durchschnitt($champions[$player["championId"]]["gold"], $player["totalGold"]);
                            
                            if(isset($player["kills"]) && $player["kills"] > 0){
                                 $champions[$player["championId"]]["kills"] = durchschnitt($champions[$player["championId"]]["kills"], $player["kills"]);
                            } else {
                                 $champions[$player["championId"]]["kills"] = durchschnitt($champions[$player["championId"]]["kills"], 0);
                            }
                            
                            if(isset($player["deaths"]) && $player["deaths"] > 0){
                                 $champions[$player["championId"]]["deaths"] = durchschnitt($champions[$player["championId"]]["deaths"], $player["deaths"]);
                            } else {
                                 $champions[$player["championId"]]["deaths"] = durchschnitt($champions[$player["championId"]]["deaths"], 0);
                            }
                            
                            if(isset($player["assists"]) && $player["assists"] > 0){
                                 $champions[$player["championId"]]["assists"] = durchschnitt($champions[$player["championId"]]["assists"], $player["assists"]);
                            } else {
                                 $champions[$player["championId"]]["assists"] = durchschnitt($champions[$player["championId"]]["assists"], 0);
                            }
                        } else {
                            $temp = array();
                            $temp["champion_data"] = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM champions WHERE champion_id = '".$GLOBALS["db_fi"]->real_escape_string($player["championId"])."'"));
                            $temp["count"]         = 1;
                            if($player["teamId"] == $game["winner"] && $game["winner"] > 0){
                                $temp["wins"] = 1;
                            } else {
                                $temp["wins"] = 0;
                            }
                            $temp["lasthits"] = $player["minionsKilled"];
                            $temp["gold"]     = $player["totalGold"];
                            
                            if(isset($player["kills"]) && $player["kills"] > 0){
                                $temp["kills"] = $player["kills"];
                            } else {
                                $temp["kills"] = 0;
                            }
                            
                            if(isset($player["deaths"]) && $player["deaths"] > 0){
                                $temp["deaths"] = $player["deaths"];
                            } else {
                                $temp["deaths"] = 0;
                            }
                            
                            if(isset($player["assists"]) && $player["assists"] > 0){
                                $temp["assists"] = $player["assists"];
                            } else {
                                $temp["assists"] = 0;
                            }
                            $champions[$player["championId"]] = $temp;
                        }
                    }
                }
            }
        }
        
        $champions_template = "";
        foreach($champions as $champ){
            $tmpl_temp = new template;
            $tmpl_temp->load("statistics/tournament_champ");
            foreach($champ["champion_data"] as $column => $value){
                $tmpl_temp->assign("CHAMPION_".trim(strtoupper($column)), $value);
            }
            $tmpl_temp->assign("COUNT", $champ["count"]);
            $tmpl_temp->assign("LASTHITS", round($champ["lasthits"], 1));
            $tmpl_temp->assign("GOLD", number_format($champ["gold"], 0, ",", "."));
            $tmpl_temp->assign("KILLS", round($champ["kills"]));
            $tmpl_temp->assign("DEATHS", round($champ["deaths"]));
            $tmpl_temp->assign("ASSISTS", round($champ["assists"]));
            $tmpl_temp->assign("WINS", $champ["wins"]);
            $tmpl_temp->assign("WINRATE", str_replace(".", ",", round(($champ["wins"] / $champ["count"] * 100), 1)));
            $champions_template .= $tmpl_temp->display();
        }
        
        $template->assign("SITE_TITLE", "Esports-Statistiken: ".$tournament["name"]);
        $template->assign("CHAMPIONS", $champions_template);
        $template->assign("GAMES_COUNT", $games_count);
        $template->assign("MATCHES_COUNT", $matches_count);
        $template->assign("TOURNAMENT_ROUND", $tournament_round);
        $tmpl = $template->display(true);
        $tmpl = $template->operators();
        echo $tmpl;
    }
} elseif(isset($_GET["load_tournaments_tournaments"])){
    if(isset($_POST["league"])){
        $tournaments = "";
        $tournaments_query = $GLOBALS["db_fi"]->query("SELECT * FROM esports_tournament WHERE league_id = '".$GLOBALS["db_fi"]->real_escape_string(trim($_POST["league"]))."' ORDER BY id DESC");
        while($tournament = $GLOBALS["db_fi"]->fetch_object($tournaments_query)){
            $tournament_element = new template;
            $tournament_element->load("statistics/index_tournament_el");
            foreach((array) $tournament as $column => $value){
                $tournament_element->assign(trim(strtoupper($column)), $value);
            }
            $tournaments .= $tournament_element->display();
        }
        echo $tournaments;
    } else {
        echo "Error";
    }
} else {
    $leagues  = "";
    $leagues_query = $GLOBALS["db_fi"]->query("SELECT * FROM esports_league ORDER BY name ASC");
    while($league = $GLOBALS["db_fi"]->fetch_object($leagues_query)){
        $tournament_tmpl = new template;
        $tournament_tmpl->load("statistics/index_league_el");
        foreach((array)$league as $column => $value){
            $tournament_tmpl->assign(strtoupper($column), $value);
        }
        $leagues .= $tournament_tmpl->display();
    }
    
    $template = new template;
    $template->load("statistics/index");
    $template->assign("SITE_TITLE", "Esports-Statistiken");
    $template->assign("LEAGUES", $leagues);
    $tmpl = $template->display(true);
    $tmpl = $template->operators();
    echo $tmpl;
}