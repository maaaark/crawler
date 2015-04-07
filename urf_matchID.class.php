<?php

class URF_matchID_parser {
	private $region = "euw";

	public function __construct($timestamp, $region){
		$this->timestamp = $timestamp;
		$this->region    = $region;
	}

	public function parse(){
		$out     = array("status" => "green", "message" => "");
		$content = curl_file("https://".$this->region.".api.pvp.net/api/lol/".$this->region."/v4.1/game/ids?beginDate=".$this->timestamp."&api_key=".RIOT_KEY);
		$json    = json_decode($content["result"], true);

		if(isset($json["status"]) && isset($json["status"]["message"])){
			if(trim($json["status"]["message"]) == "beginDate is invalid"){
				$out["message"] = "Riot stellt in dieser Minute gerade keine MatchIDs zur Verf&uuml;gung.";
				$out["status"]  = "orange";
			} else {
				$out["message"] = "<b>API-Message:</b> ".$json["status"]["message"]."<br/>".$content["info"]["url"];
				$out["status"]  = "red";
			}
		} else {
			if(isset($content["info"]["http_code"]) && $content["info"]["http_code"] == 200){
				$count       = 0;
				$count_added = 0;
				foreach($json as $matchID){
					$count++;
					$check = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT * FROM urf_matchIDs WHERE matchID = '".$GLOBALS["db"]->real_escape_string($matchID)."'
																											 AND region = '".$GLOBALS["db"]->real_escape_string($this->region)."'"));

					if(!isset($check["id"]) || $check["id"] < 1){
						$insert = $GLOBALS["db"]->query("INSERT INTO urf_matchIDs SET matchID = '".$GLOBALS["db"]->real_escape_string($matchID)."',
																					  region  = '".$GLOBALS["db"]->real_escape_string($this->region)."',
																					  added   = '".$GLOBALS["db"]->real_escape_string(date("Y-m-d H:i:s"))."',
																					  date    = '".$GLOBALS["db"]->real_escape_string(date("Y-m-d H:i:s", $this->timestamp))."'");
						$count_added++;
					}
				}
				$out["message"] = "Es wurden ".$count." URF-MatchIDs gefunden. Davon waren <b>".$count_added."</b> unbekannt und wurden gespeichert.";
			} else {
				$out["message"] = "<b>Kritischer CURL Fehler</b> HTTP-Code=".$content["info"]["http_code"]."<br/>".$content["info"]["url"];
				$out["status"]  = "red";
			}
		}
		return $out;
	}
}

?>