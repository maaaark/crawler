<div class="right_options">
   <button onclick="self.location.href='index.php?parser=esports&league={LEAGUE_ID_INTERN}&tournament={ID}&loadschedule'">Spielplan aktualisieren</button>
   <button onclick="self.location.href='index.php?parser=esports&league={LEAGUE_ID_INTERN}&tournament={ID}&settings'">Turnier Einstellungen</button>
   <button onclick="self.location.href='index.php?parser=esports&league={LEAGUE_ID_INTERN}'">Zur&uuml;ck</button>
</div>
<h1 class="site_title">Turnier &Uuml;bersicht <span>> {NAME}</h1>

<div class="box_row">
   <div class="main_box half">
      <div class="box_title">Tabelle des Turniers</div>
      <div class="box_content">
         {if STANDINGS}
            <table class="table">
               <thead>
                  <th>Rang</th>
                  <th>Team</th>
                  <th>Wins</th>
                  <th>Losses</th>
               </thead>
               <tbody>
                  {STANDINGS}
               </tbody>
            </table>
         {else}
            <div style="padding: 25px;color: rgba(0,0,0,0.5);text-align: center;">
               Es wurde noch keine Tabelle dieses Turniers geladen
            </div>
         {/if}
      </div>
   </div>

   <div class="main_box half">
      <div class="box_title">Teilnehmende Teams</div>
      <div class="box_content">
         <div style="padding: 25px;color: rgba(0,0,0,0.5);text-align: center;">
            Diese &Uuml;bersicht steht in wenigen Tagen zur verf&uuml;gung ...
         </div>
      </div>
   </div>
</div>

<div class="main_box">
   <div class="box_title">Spiele des Turniers</div>
   <div class="box_content">
      <table class="table">
         <thead>
            <th>ID</th>
            <th>Name</th>
            <th>Optionen</th>
         </thead>
         <tbody>
            {MATCHES_LIST}
         </tbody>
      </table>
   </div>
</div>