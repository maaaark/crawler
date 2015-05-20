
<?php
require_once dirname(__FILE__).'/lib/config.php';
echo "<body>Aktuelle LoL-Version: ".GAME_VERSION."<hr/>";

$league_spider = new LeagueSpider();
$league_spider->run(LEAGUE_SPIDER_NORMAL_MODE);

$log = $league_spider->getLog();
echo "<pre>";print_r($log);echo "</pre>";