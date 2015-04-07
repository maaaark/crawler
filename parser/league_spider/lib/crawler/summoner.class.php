<?php

class Summoner {
	private $id;
	private $status;
	private $no_result_count;
	
	public function __construct($summoner_id, $no_result_count = 1){
		$this->id 				  = $summoner_id;
		$this->no_result_count = $no_result_count;
	}
	
	// Sucht alle Summoner-IDs aus der Liga
	public function analyseLeague(){
		$content 	= curl_file("https://euw.api.pvp.net/api/lol/euw/v2.5/league/by-summoner/".trim($this->id)."?api_key=".RIOT_KEY);
		$json    	= json_decode($content["result"], true);
		echo $this->id."<br/>";
		if(isset($json[$this->id][0]["entries"])){
			foreach($json[$this->id][0]["entries"] as $player){
				$status = $this->handleSummoner($player["playerOrTeamId"]);
				if($status == true){
					$this->status = true;
				}
			}
		}
		
		if($this->status){
			return true;
		}
		return false;
	}

	// Bearbeitet diese Summoner IDs
	private function handleSummoner($summoner_id){
		$status = false;
		$check  = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT * FROM lol_league_parser_summoner WHERE id = '".trim($summoner_id)."'"));
		$need_update = true;
		if(isset($check["id"]) && $check["id"] > 0 && isset($check["last_update"]) && $check["last_update"] != "0000-00-00 00:00:00"){
			$date1       = date('Y-m-d H:i:s');
			$date2       = $check["last_update"];
			
			$diff        = abs(strtotime($date2) - strtotime($date1));
			$mins        = floor($diff / 60);
			if($mins < SUMMONER_UPDATE_WAITING){
			   $need_update = false;
			}
		}
		
		if($need_update){
			echo "<div style='margin-top:25px;padding:10px;background:rgba(0,0,0,0.1);border:1px solid rgba(0,0,0,0.2);'><b><u>Summoner ".$summoner_id."</u></b><br/>";
			$matchhistory = new Matchhistory($summoner_id);
			$status = $matchhistory->analyse();
			echo "</div>";
		}
		
		if($status == false){
			$this->no_result_count++;
			if($this->no_result_count >= CHANGE_TO_SID_MODE && USE_SID_MODE){
				echo "<b>MODUS WIRD GEWECHSELT</b>";
				$datei = fopen("log/temp/change_mode.txt","w");
				rewind($datei);
				fwrite($datei, "TRUE");
				fclose($datei);
				die();
			}
		}
		return $status;
	}
	
	public function getNoResultCount(){
		return $this->no_result_count;
	}
}

?>
