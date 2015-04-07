<?php

class Start {
	private $summoner_count;
	private $no_result_count;
	
	public function __construct(){
		$this->summoner_count  = 0;
		$this->no_result_count = 1;
	}
	
	// Durchläuft Featured Games und sucht nach neuen Spielen und Spielern
	public function run($allowed_leagues, $allowed_queues){
		$content = curl_file("https://euw.api.pvp.net/observer-mode/rest/featured?api_key=".RIOT_KEY);
		$json    = json_decode($content["result"], true);
		
		if(isset($json["gameList"])){
			foreach($json["gameList"] as $game){
				if(check_array($allowed_queues, $game["gameQueueConfigId"]) && isset($game["participants"]) && is_array($game["participants"])){
					// Spiel-Warteschlange passt
					$this->checkPlayer($game, $allowed_leagues);
				}
			}
		}
	}
	
	// Guckt ob ein Summoner in der richtigen Liga spielt
	private function checkPlayer($game, $allowed_leagues){
		$player       = array();
		$player_ids   = "";
		$player_names = "";
		foreach($game["participants"] as $pl_el){
			if(isset($pl_el["summonerName"])){
				$player_names .= str_replace(" ", "%20", trim($pl_el["summonerName"])).",";
			}
		}
		
		$content = curl_file("https://euw.api.pvp.net/api/lol/euw/v1.4/summoner/by-name/".trim($player_names)."?api_key=".RIOT_KEY);
		$json    = json_decode($content["result"], true);
		foreach($json as $el){
			if(isset($el["id"])){
				$temp        = array("id" => $el["id"]);
				$player[]    = $temp;
				$player_ids .= $el["id"].",";
			}
		}
		
		$content 	= curl_file("https://euw.api.pvp.net/api/lol/euw/v2.5/league/by-summoner/".trim($player_ids)."/entry?api_key=".RIOT_KEY);
		$json    	= json_decode($content["result"], true);
		
		foreach($json as $summoner_id => $summoner){ // Summoner-IDS durchlaufen
			foreach($summoner as $queue){ // Queue Einträge des Summoners durchlaufen (Solo, 3er, 5er, ...)
				if(isset($queue["queue"]) && trim($queue["queue"]) == "RANKED_SOLO_5x5"){
					if(check_array($allowed_leagues, strtolower($queue["tier"])) && isset($summoner_id) && $summoner_id > 0){
						$this->handleSummoner($summoner_id); // SummonerHandle aufrufen wenn alles stimmt
					}
				}
			}
		}
	}
	
	// Summoner die die Bedingungen erfüllen landen hier
	private function handleSummoner($summoner_id){
		if(SUMMONER_PARSE_LIMIT == 0 || SUMMONER_PARSE_LIMIT > $this->summoner_count){
			echo "Summoner-Analysieren:<br/>";
			$this->summoner_count++;
			$summoner = new Summoner($summoner_id, $this->no_result_count);
			$status = $summoner->analyseLeague();
			if($status){
				// es wurden spieler gefunden die neue spiele hatten
			} else {
				$this->no_result_count = $this->no_result_count + $summoner->getNoResultCount();
			}
		}
		
		if($this->no_result_count >= CHANGE_TO_SID_MODE && USE_SID_MODE){
			echo "<b>MODUS WIRD GEWECHSELT</b>";
			$datei = fopen("log/temp/change_mode.txt","w");
			rewind($datei);
			fwrite($datei, "TRUE");
			fclose($datei);
			die();
		}
	}
}

?>
