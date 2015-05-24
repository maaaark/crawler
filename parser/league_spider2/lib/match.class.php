<?php

class Match {
	private $match_id;
	private $logger;
	private $region;
	
	public function __construct($id, $logger, $region){
		$this->match_id = $id;
		$this->logger   = $logger;
		$this->region   = $region;
	}
	
	public function analyse(){
		$content = curl_file(API_REGION."/api/lol/".trim(strtolower($this->region))."/v2.2/match/".trim($this->match_id)."?api_key=".RIOT_KEY);
		if(check_curl($content)){
			$json    = json_decode($content["result"], true);		
			
			// Spieler laden
			if(isset($json["participants"])){
				foreach($json["participants"] as $player){
					$this->analyse_player($player);
				}
			}
			
			// Bans laden
			$this->analyse_bans($json);
			
			// Summoner-Spells laden
			$this->analyse_summoner_spells($json);
			
			// Unbekannte Summoner-IDs speichern
			$this->fetch_new_summoner($json);

			// Match speichern
			$GLOBALS["db"]->query("INSERT INTO lol_league_parser_matches SET id = '".trim($this->match_id)."', patch = '".GAME_VERSION."', region = '".$GLOBALS["db"]->real_escape_string(trim(strtolower($this->region)))."'");
			return true;
		}
		// Wenn man bis hierhin kommt: Fehler
		$this->logger->log_curl_error("error loading match#".trim($this->match_id), $content);
		return false;
	}
	
	private function analyse_player($player){
		$array = array();
		$array["champion"]              = $player["championId"];
		$array["spell1"]                = $player["spell1Id"];
		$array["spell2"]                = $player["spell2Id"];
		$array["team"]                  = $player["teamId"];
		$array["winner"]                = $player["stats"]["winner"];
		$array["kills"]                 = $player["stats"]["kills"];
		$array["deaths"]                = $player["stats"]["deaths"];
		$array["assists"]               = $player["stats"]["assists"];
		$array["lasthits"]              = $player["stats"]["minionsKilled"];
		$array["lasthits_jungle"]       = $player["stats"]["neutralMinionsKilled"];
		$array["lasthits_jungle_team"]  = $player["stats"]["neutralMinionsKilledTeamJungle"];
		$array["lasthits_jungle_enemy"] = $player["stats"]["neutralMinionsKilledEnemyJungle"];
		$array["gold_earned"]           = $player["stats"]["goldEarned"];
		$array["gold_spent"]            = $player["stats"]["goldSpent"];
		
		$this->save($array);
	}

	private function analyse_bans($json){
		// Bans laden
		if(isset($json["teams"]) && is_array($json["teams"])){
			foreach($json["teams"] as $team){
				if(isset($team["bans"]) && is_array($team["bans"])){
					foreach($team["bans"] as $ban){
						$this->addBan($ban["championId"], $ban["pickTurn"]);
					}
				}
			}
		}
	}
	
	private function analyse_summoner_spells($json){
      if(isset($json["participants"]) && is_array($json["participants"])){
         foreach($json["participants"] as $player){
            if(isset($player["spell1Id"]) && isset($player["spell2Id"]) && isset($player["championId"])){
               if(isset($player["stats"]) && isset($player["stats"]["winner"]) && $player["stats"]["winner"] > 0){
                  $winner = 1;
               } else {
                  $winner = 0;
               }
               
               $check = "SELECT * FROM lol_champions_stats_summonerspells WHERE patch    = '".GAME_VERSION."' AND
                                                                                region   = '".$GLOBALS["db"]->real_escape_string(trim(strtolower($this->region)))."' AND
                                                                                champion = '".$GLOBALS["db"]->real_escape_string($player["championId"])."' AND
                                                                                spell1   = '".$GLOBALS["db"]->real_escape_string($player["spell1Id"])."' AND
                                                                                spell2   = '".$GLOBALS["db"]->real_escape_string($player["spell2Id"])."'
                                                                                OR
                                                                                patch    = '".GAME_VERSION."' AND
                                                                                region   = '".$GLOBALS["db"]->real_escape_string(trim(strtolower($this->region)))."' AND
                                                                                champion = '".$GLOBALS["db"]->real_escape_string($player["championId"])."' AND
                                                                                spell2   = '".$GLOBALS["db"]->real_escape_string($player["spell1Id"])."' AND
                                                                                spell1   = '".$GLOBALS["db"]->real_escape_string($player["spell2Id"])."'";
               $data = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query($check));
               
               if(isset($data["id"]) && $data["id"] > 0 && isset($data["count"])){
                  $new_count = intval($data["count"]) + 1;
                  $sql       = "UPDATE lol_champions_stats_summonerspells SET count = '".$GLOBALS["db"]->real_escape_string($new_count)."', wins = '".$GLOBALS["db"]->real_escape_string($data["wins"] + $winner)."' WHERE id = '".$GLOBALS["db"]->real_escape_string($data["id"])."'";
               } else {
                  $sql       = "INSERT INTO lol_champions_stats_summonerspells SET count = '1',
                                                                                   spell1 = '".$GLOBALS["db"]->real_escape_string($player["spell1Id"])."',
                                                                                   spell2 = '".$GLOBALS["db"]->real_escape_string($player["spell2Id"])."',
                                                                                   patch  = '".$GLOBALS["db"]->real_escape_string(GAME_VERSION)."',
                                                                                   region = '".$GLOBALS["db"]->real_escape_string(trim(strtolower($this->region)))."',
                                                                                   wins   = '".$GLOBALS["db"]->real_escape_string($winner)."',
                                                                                   champion = '".$GLOBALS["db"]->real_escape_string($player["championId"])."'";
               }
               $GLOBALS["db"]->query($sql);
            }
         }
      }
	}

	private function fetch_new_summoner($json){
		if(isset($json["participantIdentities"]) && is_array($json["participantIdentities"])){
			foreach($json["participantIdentities"] as $summoner){
				if(isset($summoner["player"]["summonerId"])){
					$check = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT id FROM lol_league_parser_summoner WHERE id = '".$GLOBALS["db"]->real_escape_string($summoner["player"]["summonerId"])."' AND region = '".$GLOBALS["db"]->real_escape_string(trim(strtolower($this->region)))."'"));
					if(isset($check["id"]) && $check["id"] > 0){
						// Summoner bereits bekannt -> nichts machen
					} else {
						$GLOBALS["db"]->query("INSERT INTO lol_league_parser_summoner SET id = '".$GLOBALS["db"]->real_escape_string($summoner["player"]["summonerId"])."', region = '".$GLOBALS["db"]->real_escape_string(trim(strtolower($this->region)))."'");
					}
				}
			}
		}
	}
	
	private function save($array){
		$check = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT * FROM lol_champions_stats WHERE champion = '".$array["champion"]."' AND patch = '".$GLOBALS["db"]->real_escape_string(GAME_VERSION)."' AND region = '".$GLOBALS["db"]->real_escape_string(strtolower(trim($this->region)))."'"));
		
		if(isset($check["id"]) && $check["id"] > 0){
			$sql 		 = "UPDATE lol_champions_stats SET ";
			$sql_type = "update";
		} else {
			$sql 		 = "INSERT INTO lol_champions_stats SET region = '".$GLOBALS["db"]->real_escape_string(strtolower(trim($this->region)))."', champion = '".$array["champion"]."', patch = '".$GLOBALS["db"]->real_escape_string(GAME_VERSION)."', ";
			$sql_type = "insert";
		}
		
		$wins = 0;
		if(isset($array["winner"]) && $array["winner"] > 0){
			$wins = 1;
		}
		if(isset($check["wins"]) && $check["wins"] > 0){
         $wins = $wins + $check["wins"];
		}
		
		$sql .= "wins                    = '".$wins."'";
		$sql .= ", kills                 = '".$this->durchschnitt($check, "kills", $array["kills"])."'";
		$sql .= ", deaths                = '".$this->durchschnitt($check, "deaths", $array["deaths"])."'";
		$sql .= ", assists               = '".$this->durchschnitt($check, "assists", $array["assists"])."'";
		$sql .= ", lasthits              = '".$this->durchschnitt($check, "lasthits", $array["lasthits"])."'";
		$sql .= ", lasthits_jungle       = '".$this->durchschnitt($check, "lasthits_jungle", $array["lasthits_jungle"])."'";
		$sql .= ", lasthits_jungle_team  = '".$this->durchschnitt($check, "lasthits_jungle_team", $array["lasthits_jungle_team"])."'";
		$sql .= ", lasthits_jungle_enemy = '".$this->durchschnitt($check, "lasthits_jungle_enemy", $array["lasthits_jungle_enemy"])."'";
		$sql .= ", gold_earned           = '".$this->durchschnitt($check, "gold_earned", $array["gold_earned"])."'";
		$sql .= ", gold_spent            = '".$this->durchschnitt($check, "gold_spent", $array["gold_spent"])."'";
      
      
		if($sql_type == "insert"){
			$sql .= ", matches_count = '1'";
		}
		
		if($sql_type == "update"){
			$count = $check["matches_count"] + 1;
			$sql .= ", matches_count = '".$count."' WHERE id = '".$GLOBALS["db"]->real_escape_string($check["id"])."'";
		}
		
		// QUERY ausführen
		$GLOBALS["db"]->query($sql);
	}
	
	private function durchschnitt($data, $column, $value){
		if(isset($data[$column]) && $data[$column] > 0){
			$calc = $data[$column] + $value;
			return $calc / 2;
		}
		return $value;
	}
	
	private function addBan($champion_id, $pick){
		$check = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT * FROM lol_champions_stats_bans WHERE champion = '".$GLOBALS["db"]->real_escape_string($champion_id)."' AND
                                                                                                                 region   = '".$GLOBALS["db"]->real_escape_string(strtolower(trim($this->region)))."' AND 
                                                                                                                 patch    = '".$GLOBALS["db"]->real_escape_string(GAME_VERSION)."'"));
		if(isset($check["id"]) && $check["id"] > 0){
			$pick_count 	= $check["ban_".trim($pick)] + 1;
			$new_ban_count = $check["bans"] + 1;
			$sql 				= "UPDATE lol_champions_stats_bans SET bans = '".$new_ban_count."', ban_".trim($pick)." = '".$pick_count."' WHERE id = '".$check["id"]."' AND region = '".$GLOBALS["db"]->real_escape_string(strtolower(trim($this->region)))."'";
		} else {
			$sql = "INSERT INTO lol_champions_stats_bans SET champion = '".trim($champion_id)."', bans = '1', ban_".trim($pick)." = '1', patch = '".$GLOBALS["db"]->real_escape_string(GAME_VERSION)."', region = '".$GLOBALS["db"]->real_escape_string(strtolower(trim($this->region)))."'";
		}
		$query = $GLOBALS["db"]->query($sql);
	}
}

?>
