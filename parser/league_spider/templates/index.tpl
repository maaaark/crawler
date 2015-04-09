<h1 class="site_title">League Spider <span>>&Uuml;bersicht</span></h1>

<div class="main_box">
	<div class="box_title">Aktuelle Crawler Daten</div>
	<div class="box_content">
		<div class="message {LAST_CRAWLING_STATUS}">
			Zuletzt gecrawled vor <b>{LAST_CRAWLING_MINS}</b> Minuten ({LAST_CRAWLING})
		</div>
		Aktueller Patch: {LEAGUE_SPIDER_GAME_VERSION}<br/>
		Matches gecrawled: {MATCHES_CURRENT_PATCH}<br/>
		Verschiedene Champions gefunden: {CHAMPIONS_CURRENT_PATCH}<br/>
		Bekannte Summoners: {POSSIBLE_SUMMONERS}
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