<h1 class="site_title">Turnier-Statistiken: {NAME}</h1>

<div class="box_row">
    <div class="main_box half">
        <div class="box_title">Turnier Informationen:</div>
        <div class="box_content">
            <table class="table">
                <tr>
                    <td>Gespielte Matches</td>
                    <td>{MATCHES_COUNT}</td>
                </tr>
                <tr>
                    <td>Gespielte Spiele</td>
                    <td>{GAMES_COUNT}</td>
                </tr>
                <tr>
                    <td>Aktuelle Turnier-Runde (Spieltag)</td>
                    <td>{TOURNAMENT_ROUND}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="main_box half">
        <div class="box_title">Leere Box</div>
        <div class="box_content">
            <div style="padding: 25px;color: rgba(0,0,0,0.5);text-align: center;">
                Diese Box wird bald gef&uuml;llt :D
            </div>
        </div>
    </div>
</div>

<h2>Gespielte Champions:</h2>
<table class="table sort_table" id="tournament_champ_table">
    <thead>
        <th>Name</th>
        <th>Anzahl der Picks</th>
        <th>Gewonnen</th>
        <th>Winrate</th>
        <th>Kills / Deaths / Assists</th>
        <th>Lasthits</th>
        <th>Gold gesammelt</th>
    </thead>
    <tbody>
        {CHAMPIONS}
    </tbody>
</table>

<script>
   $(document).ready(function(){
      $("#tournament_champ_table").tablesorter({sortList: [[1,1]]});
   });
</script>