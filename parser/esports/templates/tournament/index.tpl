<div class="right_options">
   <button onclick="self.location.href='index.php?parser=esports&league={LEAGUE_ID_INTERN}&tournament={ID}&loadschedule'">Spielplan aktualisieren</button>
   <button onclick="self.location.href='index.php?parser=esports&league={LEAGUE_ID_INTERN}'">Zur&uuml;ck</button>
</div>
<h1 class="site_title">Turnier &Uuml;bersicht <span>> {NAME}</h1>

<div class="main_box">
   <div class="box_title">Spiele des Turniers</div>
   <div class="box_content">
      <table class="table">
         <thead>
            <th>ID</th>
            <th>Name</th>
         </thead>
         <tbody>
            {MATCHES_LIST}
         </tbody>
      </table>
   </div>
</div>