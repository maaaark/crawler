<?php

class LeagueSpider {
    private $logger;
    private $region;
    
    public function __construct(){
        $this->logger = new LeagueSpiderLog();
        $this->logger->log("LeagueSpider init");
        
        $this->setRegion("euw");
    }
    
    /*
     * $mode = 1 -> normaler Modus
    */
    public function run($mode){
        if($mode == 1){
            require_once dirname(__FILE__).'/normal_mode.class.php';
            $normal_mode = new NormalMode($this->logger, $this->region);
            $normal_mode->run();
        }
    }
    
    public function setRegion($value){
        if($value == "na"){
            $this->region = "na";
            define("API_REGION", "https://na.api.pvp.net");
        } else {
            $this->region = "euw";
            define("API_REGION", "https://euw.api.pvp.net");
        }
    }
    
    public function getLog($type = "console"){
        return $this->logger->get("html");
    }
}