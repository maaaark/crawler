<?php

class QueueMode {
   private $logger;
   
   public function __construct($logger){
      $this->logger = $logger;
      $this->logger->log("Queue-Mode init");
   }
   
   public function run(){
      $check  = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT COUNT(*) FROM league_spider_queue"));
      if(isset($check["COUNT(*)"]) && $check["COUNT(*)"] > 100){
         $count = 0;
         $games_query = $GLOBALS["db"]->query("SELECT * FROM league_spider_queue ORDER BY RAND() LIMIT ".QUEUE_MATCHES_LIMIT);
         while($game = $GLOBALS["db"]->fetch_object($games_query)){
            $match   = new Match($game->match_id, $this->logger, $game->region);
            $analyse = $match->analyse();
            if($analyse){
               $count++;
            }
            $GLOBALS["db"]->query("DELETE FROM league_spider_queue WHERE match_id = '".$GLOBALS["db"]->real_escape_string($game->match_id)."'
                                                                     AND region   = '".$GLOBALS["db"]->real_escape_string($game->region)."'");
         }
         $this->logger->log("Succesfully analysed ".$count." new matches");
         
      } else {
         $status = $this->fill_queue();
      }
   }
   
   private function fill_queue(){
      $status = $this->fetch_new_summoner();
      if($status){
         $this->logger->log("Fill queue");
         
         $query = $GLOBALS["db"]->query("SELECT * FROM lol_league_parser_summoner WHERE last_update < '".date('Y-m-d H:i:s', time() - SUMMONER_UPDATE_WAITING * 60)."' ORDER BY RAND() LIMIT ".QUEUE_SUMMONER_LIMIT);
         $count = 0;
         while($row = $GLOBALS["db"]->fetch_object($query)){
            $count = $count + $this->getMatches($row->id, $row->region);
         }
         $this->logger->log("Filled the queue with ".$count." new match-ids");
      } else {
         $this->logger->log("Fetched new Summoner -> stop here");
      }
   }
   
   private function getMatches($summoner_id, $region){
		$count   = 0;
		$content = curl_file(LeagueSpider::getRegion($region)."/api/lol/".trim($region)."/v2.2/matchhistory/".trim($summoner_id)."?rankedQueues=RANKED_SOLO_5x5&api_key=".RIOT_KEY);
		
		if(check_curl($content)){
			$json    = json_decode($content["result"], true);
			
			if(isset($json["matches"])){
				foreach($json["matches"] as $match){
					if(isset($match["matchVersion"]) && isset($match["matchId"])){
						if(strpos("platzhalter_".$match["matchVersion"], GAME_VERSION) > 0){
							$status = $this->handleMatch($match["matchId"], $region);
							if($status){
								$count++;
							}
						}
					}
				}
			}
		} else {
			$this->logger->log_curl_error("loading matchhistory summoner#".trim($summoner_id), $content);
		}
		return $count;
	}

	private function handleMatch($match_id, $region){
		$check = $GLOBALS["db"]->fetch_array($GLOBALS["db"]->query("SELECT * FROM lol_league_parser_matches WHERE id = '".$GLOBALS["db"]->real_escape_string($match_id)."' AND region = '".$GLOBALS["db"]->real_escape_string($region)."'"));
		if(isset($check["id"]) && $check["id"] > 0){
			// Match bereits analysiert:
			return false;
		}
		
		$insert = "INSERT INTO league_spider_queue SET region   = '".$GLOBALS["db"]->real_escape_string($region)."',
                                                     patch    = '".$GLOBALS["db"]->real_escape_string(GAME_VERSION)."',
                                                     match_id = '".$GLOBALS["db"]->real_escape_string($match_id)."'";
      $query  = $GLOBALS["db"]->query($insert);
		return true;
	}
   
   private function fetch_new_summoner(){
      $status  = true; // Wenn keine Regionen aktualisiert werden mussten -> true zurückgeben
      $regions = LeagueSpider::getAllowedRegions();
      foreach($regions as $region){
         $this->logger->log("Check Summoner for ".$region);
         $query = $GLOBALS["db"]->query("SELECT COUNT(*) FROM lol_league_parser_summoner WHERE region      = '".$GLOBALS["db"]->real_escape_string($region)."'
                                                                                           AND last_update < '".date('Y-m-d H:i:s', time() - SUMMONER_UPDATE_WAITING * 60)."'");
         $data  = $GLOBALS["db"]->fetch_array($query);
         
         if(isset($data["COUNT(*)"]) && $data["COUNT(*)"] > 20){
            $this->logger->log("There are more then 20 Summoners (".$region."): dont fetch new summoners");
         } else {
            $this->logger->log("Less then 20 Summoners (".$region."): fetch new summoners");
            
            require_once dirname(__FILE__).'/featured_games.class.php';
            $featured_games = new FeaturedGames($this->logger, $region);
            $featured_games->fetch_new_summoner();
            $status = false;
         }
      }
      return $status;
   }
}