<?php

class LeagueSpider {
    private $logger;
    private $region;
    
    public function __construct($region = "euw"){
        $this->logger = new LeagueSpiderLog();
        $this->logger->log("LeagueSpider init");
        
        $this->setRegion(strtolower(trim($region)));
    }
    
    /*
     * $mode = 1 -> normaler Modus
    */
    public function run($mode){
        if($mode == 1){
            require_once dirname(__FILE__).'/normal_mode_analyse.class.php';
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
        return $this->logger->get($type);
    }
}