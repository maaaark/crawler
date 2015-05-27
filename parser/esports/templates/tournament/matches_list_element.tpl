<tr{if NO_WINNER} style="color: rgba(0,0,0,0.6);"{/if}>
   <td>{ID}</td>
   <td>{if NO_WINNER}<span style="font-weight: bold;">Noch nicht gespielt:</span> {/if}{NAME}</td>
   <td><button onclick="self.location.href='index.php?parser=esports&league={LEAGUE_ID_INTERN}&tournament={TOURNAMENT_ID_INTERN}&loadmatch={ID}'">Match Daten aktualisieren</button></td>
</tr>