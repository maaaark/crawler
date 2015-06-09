<h1 class="site_title">League Spider <span>>&Uuml;bersicht</span></h1>

<div class="main_box">
	<div class="box_title">Aktuelle Crawler Daten</div>
	<div class="box_content">
		<div class="message {LAST_CRAWLING_STATUS}">
			Zuletzt gecrawled vor <b>{LAST_CRAWLING_MINS}</b> Minuten ({LAST_CRAWLING})
		</div>
		
		<table class="table">
         <tr><td class="title">Aktueller Patch</td><td>{LEAGUE_SPIDER_GAME_VERSION}</td></tr>
         <tr><td class="title">Matches gecrawled</td><td>{MATCHES_CURRENT_PATCH}</td></tr>
         <tr><td class="title">Verschiedene Champions gefunden</td><td>{CHAMPIONS_CURRENT_PATCH}</td></tr>
         <tr><td class="title">"Frische/Neue" Summoner</td><td>{POSSIBLE_SUMMONERS_UNPARSED}</td></tr>
         <tr><td class="title">Bekannte Summoners</td><td>{POSSIBLE_SUMMONERS}</td></tr>
      </table>
      
      <style>
         .region_matches_count {
            width: 33.33333%;
            float: left;
            text-align: center;
            border-right: 1px solid rgba(0,0,0,0.1);
            box-sizing: border-box;
         }
         
         .region_matches_count.last {
            border-right:none;
         }
      </style>
      <h2>Technische-Daten</h2>
      <table class="table">
         <tr><td class="title">Laufende Crawler Prozesse</td><td>{RUNNING_CRAWLER_COUNT}</td></tr>
         <tr><td class="title">Queue-Mode aktiv</td><td>{if QUEUE_MODE_RUNNING}<span style="color: green;">l&auml;uft</span>{else}<span style="color: red;">nicht aktiv</span>{/if}</td></tr>
         <tr><td class="title">EUW Crawler aktiv</td><td>{RUNNING_CRAWLER_EUW}</td></tr>
         <tr><td class="title">NA Crawler aktiv</td><td>{RUNNING_CRAWLER_NA}</td></tr>
         <tr><td class="title">EUNE Crawler aktiv</td><td>{RUNNING_CRAWLER_EUNE}</td></tr>
         <tr>
            <td colspan="2" style="background: rgba(247, 247, 247, 1);">
                <div class="region_matches_count">{MATCHES_COUNT_EUW} EUW-Matches</div>
                <div class="region_matches_count">{MATCHES_COUNT_NA} NA-Matches</div>
                <div class="region_matches_count last">{MATCHES_COUNT_EUNE} EUNE-Matches</div>
                <div style="clear:both;"></div>
            </td>
         </tr>
      </table>
	</div>
</div>

<h2>Champion-Daten von Patch {LEAGUE_SPIDER_GAME_VERSION}</h2>
{if CHAMPIONS_LIST}
<table class="table sort_table" id="champion_league_spider_table">
   <thead>
      <th class="no_mobile">ID</th>
      <th>Champion</th>
      <th>Spiele</th>
      <th class="no_mobile">Wins</th>
      <th>Winrate</th>
      <th>Kills / Tode / Assists</th>
      <th>Lasthits</th>
      <th>Gold <span class="no_mobile">gesammelt</span></th>
   </thead>
   <tbody>
      {CHAMPIONS_LIST}
   </tbody>
</table>

<script>
   $(document).ready(function(){
      $("#champion_league_spider_table").tablesorter({sortList: [[1,0]]});
   });
</script>
{/if}