<?php

/*
 * Diese Klasse lädt alle Summoner-IDs aus der Featured-Games List und lädt vorab diverse Informationen zu den Summonern
 */
class FeaturedGames {
    private $logger;
    private $region;
    
    public function __construct($logger, $region){
        $this->logger = $logger;
        $this->logger->log("FeaturedGames init");
        $this->region = $region;
    }
    
    public function fetch_new_summoner(){
        $this->logger->log("Fetch New Summoner started");
        
        $curl = curl_file(API_REGION."/observer-mode/rest/featured?api_key=".RIOT_KEY);
        if(check_curl($curl)){
            $this->logger->log("Curl success Features Games");
            
            $json = json_decode($curl["result"], true);
            if(isset($json["gameList"]) && is_array($json["gameList"])){
                $this->handle_fetch_summoner($json["gameList"]);

                $this->logger->log("Loaded new summoners (".$this->region.").");
                $this->logger->set_final_message("Loaded new summoners(".$this->region.").");
            } else {
                $this->logger->log_error("Got unknown JSON object: featured-games");
            }
        } else {
            $this->logger->log_curl_error("loading featured-games", $curl);
        }
    }
    
    private function handle_fetch_summoner($game_list){
        foreach($game_list as $element){
            if(isset($element["participants"]) && is_array($element["participants"])){
                $player_names = "";
                foreach($element["participants"] as $pl_el){
                    if(isset($pl_el["summonerName"])){
                        $player_names .= str_replace(" ", "%20", trim($pl_el["summonerName"])).",";
                    }
                }
                
                if($player_names != ""){
                    $curl = curl_file(API_REGION."/api/lol/".trim(strtolower($this->region))."/v1.4/summoner/by-name/".trim($player_names)."?api_key=".RIOT_KEY);
                    if(check_curl($curl)){
                        $this->logger->log("Curl success summoner-ids");
                        $summoner_ids = "";
                        $json         = json_decode($curl["result"], true);
                        foreach($json as $el){
                            if(isset($el["id"])){
                                $check = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT id FROM lol_league_parser_summoner WHERE id = '".$GLOBALS["db"]->real_escape_string($el["id"])."' AND region = '".$GLOBALS["db"]->real_escape_string($this->region)."'"));
                                
                                if(isset($check["id"]) && $check["id"] > 0){
                                    // Summoner bereits bekannt -> nicht neu anfügen
                                } else {
                                    if(trim($summoner_ids) != ""){
                                        $summoner_ids .= ",";
                                    }
                                    $summoner_ids .= trim($el["id"]);
                                }
                            }
                        }
                        
                        if(trim($summoner_ids) != ""){
                            $this->check_summoner_ids_league($summoner_ids);
                        }
                    } else {
                        $this->logger->log_curl_error("loading featured-games", $curl);
                    }
                }
            }
        }
    }
    
    private function check_summoner_ids_league($summoner_ids){
        $curl = curl_file(API_REGION."/api/lol/".trim(strtolower($this->region))."/v2.5/league/by-summoner/".trim($summoner_ids)."/entry?api_key=".RIOT_KEY);
        if(check_curl($curl)){
            $json = json_decode($curl["result"], true);
            
            foreach($json as $summoner_id => $summoner){
                if(isset($summoner[0]) && is_array($summoner[0])){
                    $summoner = $summoner[0];
                    
                    if(isset($summoner["queue"]) && trim(strtoupper($summoner["queue"])) == "RANKED_SOLO_5X5" && isset($summoner["tier"])){
                        global $allowed_leagues;
                        if(check_array($allowed_leagues, strtolower($summoner["tier"]))){
                            $sql    = "INSERT INTO lol_league_parser_summoner SET id     = '".$GLOBALS["db"]->real_escape_string($summoner_id)."', 
                                                                                  region = '".$GLOBALS["db"]->real_escape_string($this->region)."',
                                                                                  league = '".$GLOBALS["db"]->real_escape_string(strtolower(trim($summoner["tier"])))."'";
                            $insert = $GLOBALS["db"]->query($sql);
                        }
                    }
                }
            }
        } else {
            $this->logger->log_curl_error("loading summoner leagues by ids", $curl);
        }
    }
}