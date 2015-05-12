<tr{if HIGHLIGHT==TRUE} style="background-color: rgb(220, 236, 250);"{/if}>
   <td>{TOURNAMENT_TOURNAMENT_ID}</td>
   <td>
      {TOURNAMENT_NAME}
      {if HIGHLIGHT==TRUE}<div style="font-size: 13px;color: rgba(0,0,0,0.6);">Aktuell aktives Turnier der Liga</div>{/if}
   </td>
   <td>{TOURNAMENT_SEASON}</td>
   <td>
   	   <button onclick="self.location.href='index.php?parser=esports&league={LEAGUE_ID}&tournament={TOURNAMENT_ID}'">Spielplan anzeigen</button>
   	   <button onclick="self.location.href='index.php?parser=esports&league={LEAGUE_ID}&tournament={TOURNAMENT_ID}&settings'">Einstellungen</button>
   </td>
</tr>