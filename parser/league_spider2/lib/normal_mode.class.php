<?php

class NormalMode {
    private $logger;
    private $type;
    private $region;
    
    public function __construct($logger, $region = "euw"){
        $this->logger = $logger;
        $this->logger->log("Normal-Mode init");
        $this->region = trim($region);
    }
    
    public function run(){
        $this->logger->log("Normal-Mode started - ".$this->region);
        
        $this->type = "load_new_summoner";
        global $allowed_leagues;
        foreach($allowed_leagues as $league){
            $query = $GLOBALS["db"]->query("SELECT COUNT(*) FROM lol_league_parser_summoner WHERE league      LIKE '".$GLOBALS["db"]->real_escape_string(trim($league))."'
                                                                                              AND region      = '".$GLOBALS["db"]->real_escape_string($this->region)."'
                                                                                              AND last_update < '".date('Y-m-d H:i:s', time() - SUMMONER_UPDATE_WAITING * 60)."'");
            $data  = $GLOBALS["db"]->fetch_array($query);
            
            if(isset($data["COUNT(*)"]) && $data["COUNT(*)"] > 0){
                $this->type = "load_leagues";
                break;
            }
        }
        
        if($this->type == "load_leagues"){
            $this->load_leagues();
        } else {
            $this->load_new_summoner();
        }
    }
    
    private function load_new_summoner(){
        $this->logger->log("load_new_summoner");
        
        require_once dirname(__FILE__).'/featured_games.class.php';
        $featured_games = new FeaturedGames($this->logger, $this->region);
        $featured_games->fetch_new_summoner();
    }
    
    private function load_leagues(){
        $this->logger->log("Normal-Mode: load_leagues");

        $query = $GLOBALS["db"]->query("SELECT * FROM lol_league_parser_summoner WHERE region = '".$GLOBALS["db"]->real_escape_string(trim(strtolower($this->region)))."' AND last_update < '".date('Y-m-d H:i:s', time() - SUMMONER_UPDATE_WAITING * 60)."' LIMIT ".SUMMONER_LIMIT);
        $nums  = $GLOBALS["db"]->num_rows($query);
        $this->logger->log("Selected ".$nums."/".SUMMONER_LIMIT." summoners to update");

        $success_count = 0;
        while($row = $GLOBALS["db"]->fetch_object($query)){
            $analyse       = $this->analyse_summoner($row->id);
            $success_count = $success_count + $analyse;

            $update = $GLOBALS["db"]->query("UPDATE lol_league_parser_summoner SET last_update = '".date('Y-m-d H:i:s')."' WHERE id = '".$GLOBALS["db"]->real_escape_string($row->id)."'");
        }
        $this->logger->log($success_count." new matches were found (".$this->region.").");
        $this->logger->set_final_message($success_count." new matches were found (".$this->region.").");
    }

    private function analyse_summoner($summoner_id){
        $analyse = new NormalModeAnalyse($this->logger, $this->region);
        return $analyse->analyse($summoner_id);
    }
}