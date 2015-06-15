<?php

class NormalModeAnalyse {
	private $logger;
	private $region;

	public function __construct($logger, $region = "euw"){
		$this->logger = $logger;
		$this->region = $region;
	}

	public function analyse($summoner_id){
		$new_matches = $this->getMatches($summoner_id);
		return $new_matches;
	}

	private function getMatches($summoner_id){
		$count   = 0;
		$content = curl_file(API_REGION."/api/lol/".trim(strtolower($this->region))."/v2.2/matchhistory/".trim($summoner_id)."?rankedQueues=RANKED_SOLO_5x5&api_key=".RIOT_KEY);
		
		if(check_curl($content)){
			$json    = json_decode($content["result"], true);
			
			if(isset($json["matches"])){
				foreach($json["matches"] as $match){
					if(isset($match["matchVersion"]) && isset($match["matchId"])){
						if(strpos("platzhalter_".$match["matchVersion"], GAME_VERSION) > 0){
							$status = $this->handleMatch($match["matchId"], $match);
							if($status){
								$count++;
							}
						}
					}
				}
			}
		} else {
			$this->logger->log_curl_error("loading matchhistory summoner#".trim($summoner_id), $content);
		}
		return $count;
	}

	private function handleMatch($match_id){
		$check = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT id FROM lol_league_parser_matches WHERE id = '".$GLOBALS["db"]->real_escape_string(trim($match_id))."' AND region = '".$GLOBALS["db"]->real_escape_string(trim(strtolower($this->region)))."'"));
		if(isset($check["id"]) && $check["id"] > 0){
			// Match bereits analysiert:
			return false;
		}
		$match = new Match($match_id, $this->logger, $this->region);
		return $match->analyse();
	}
}