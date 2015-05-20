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
    }
}