<div class="right_options">
   <button onclick="self.location.href='index.php?parser=esports&league={ID}&loadtournaments'">Turniere aktualisieren</button>
   <button onclick="self.location.href='index.php?parser=esports'">Zur&uuml;ck</button>
</div>
<h1 class="site_title">{LABEL} <span>> &Uuml;bersicht</span></h1>

<div class="main_box">
   <div class="box_title">Turniere von {SHORT_NAME}</div>
   <div class="box_content">
      {if TOURNAMENTS_LIST}
         <table class="table">
            <thead>
               <th>ID</td>
               <th>Name</th>
               <th>Saison</th>
               <th>Optionen</th>
            </thead>
            <tbody>
               {TOURNAMENTS_LIST}
            </tbody>
         </table>
      {else}
         <div style="padding: 15px; color: rgba(0,0,0,0.5); text-align: center;">
            Es wurden noch keine Turniere f&uuml;r diese Liga geladen.
         </div>
      {/if}
   </div>
</div>