<?php

class Team {
	private $team_id;

	public function __construct($team_id){
		$this->team_id = $team_id;
	}

	public function save(){
		$need_update = true;
		if(isset($_SESSION["esports_parser_teams"])){
			if(strpos("platzhalter_".$_SESSION["esports_parser_teams"], "{"+trim($this->team_id)+"}") > 0){
				$need_update = false;
			}
		}
		
		if($need_update){
			$content = @file_get_contents("http://na.lolesports.com:80/api/team/".trim($this->team_id).".json");
			if($content){
				$json = json_decode($content, true);
				if(isset($json["name"]) && isset($json["acronym"]) && trim($json["acronym"]) != "" && trim($json["name"]) != ""){
					$temp = "";
					if(isset($_SESSION["esports_parser_teams"])){
						$temp = $_SESSION["esports_parser_teams"];
					}
					$_SESSION["esports_parser_teams"] = $temp."{".trim($this->team_id)."}";

					$check = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_team WHERE team_id = '".$GLOBALS["db_fi"]->real_escape_string($this->team_id)."'"));
					if(isset($check["id"]) && $check["id"] > 0){
						$sql_type = "update";
						$sql 	  = "UPDATE esports_team SET ";
					} else {
						$sql_type = "insert";
						$sql 	  = "INSERT INTO esports_team SET team_id = '".$GLOBALS["db_fi"]->real_escape_string($this->team_id)."', ";
					}

					$sql .= "acronym = '".$GLOBALS["db_fi"]->real_escape_string(trim($json["acronym"]))."'";
					$sql .= ", name = '".$GLOBALS["db_fi"]->real_escape_string(trim($json["name"]))."'";

					if(isset($json["logoUrl"]) && trim($json["logoUrl"]) != ""){
						$sql .= ", logo_riot = '".$GLOBALS["db_fi"]->real_escape_string($json["logoUrl"])."'";
					}

					if($sql_type == "update"){
						$sql .= " WHERE id = '".$GLOBALS["db_fi"]->real_escape_string($check["id"])."'";
					}
					
					if(isset($json["roster"]) && is_array($json["roster"])){
                  $this->update_players($json["roster"]);
					}

					$GLOBALS["db_fi"]->query($sql);
				} else {
					addInstantMessage("Beim Updaten des Teams #".$this->team_id." lieferte die API unbrauchbare Daten.", "red");
				}
			} else {
				addInstantMessage("Beim Updaten des Teams #".$this->team_id." ist ein API Fehler aufgetreten.", "red");
			}
		}
	}
	
	private function update_players($roster){
      foreach($roster as $player){
         $object = new Player($player["playerId"]);
         $object->short_update($player, $this->team_id);
      }
	}
}