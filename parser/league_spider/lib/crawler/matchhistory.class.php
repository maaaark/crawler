<?php

class Matchhistory {
	private $summoner_id;
	private $new_data;
	
	public function __construct($id){
		$this->summoner_id = $id;
		$this->new_data    = false;
	}
	
	// Durchläuft die Matchhistory eines Summoners und sucht Spiele vom aktuellen Patch
	public function analyse(){
		$content = curl_file("https://euw.api.pvp.net/api/lol/euw/v2.2/matchhistory/".trim($this->summoner_id)."?rankedQueues=RANKED_SOLO_5x5&api_key=".RIOT_KEY);
		$json    = json_decode($content["result"], true);
		
		if(isset($json["matches"])){
			foreach($json["matches"] as $match){
				if(isset($match["matchVersion"]) && isset($match["matchId"])){
					if(strpos("platzhalter_".$match["matchVersion"], GAME_VERSION) > 0){
						$this->handleMatch($match["matchId"], $match);
					}
				}
			}
			$check = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT id FROM lol_league_parser_summoner WHERE id = '".trim($this->summoner_id)."'"));
			if(isset($check["id"]) && $check["id"] > 0){
				$GLOBALS["db"]->query("UPDATE lol_league_parser_summoner SET last_update = '".date("Y-m-d H:i:s")."' WHERE id = '".trim($this->summoner_id)."'");
			} else {
				$GLOBALS["db"]->query("INSERT INTO lol_league_parser_summoner SET id = '".trim($this->summoner_id)."', last_update = '".date("Y-m-d H:i:s")."'");
			}
		}
		
		if($this->new_data == false){
			echo "<b>Keine neuen Spiel-Daten gefunden</b><br/>";
			return false;
		}
		return true;
	}
	
	private function handleMatch($id, $match){
		$check = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT id FROM lol_league_parser_matches WHERE id = '".$id."'"));
		if(isset($check["id"]) && $check["id"] > 0){
			// Spiel bereits eingefügt
		} else {
			$this->new_data = true;
			echo "<b>Spiel: ".$id."</b><br/>";
			$match_object = new Match($id);
			$match_object->analyse();
			echo "<hr/>";
		}
	}
}

?>
